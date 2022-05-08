(()=>{
  startApp();
  let currentVoice = undefined;
  let voiceStream = undefined;
  let running = false;
  const peer = new Peer(undefined, {
    host: '/',
    port: nodePort,
    path: '/peerjs'
  });


  navigator.mediaDevices.getUserMedia({
    audio: true,
    video: false
  }).then(stream => {
    voiceStream = stream;
    voiceStream.mute = () => {
      this.getAudioTracks().forEach(track => track.enabled = !track.enabled);
    }
    socket.on('voice-connected', (data) => {

      const call = peer.call(data.peer_id, stream);
      call.on('stream', userStream => {
        const audio = document.createElement('audio');
        audio.srcObject = userStream;
        audio.play();
      });
    });
  })

  peer.on('call', call => {
    call.answer(voiceStream);
    call.on('stream', userStream => {
      const audio = document.createElement('audio');
      audio.srcObject = userStream;
      audio.play();
    });
  });


  socket.on('user-connected', (data) => {
    createUser(data);
  });

  peer.on('open', id => {
    socket.emit('guild-connect', {
      guild_id: guild_id,
      user_id: user_id,
      peer_id: id
    });
  });


  socket.on('user-disconnected', (data) =>{
    const userImage = $(`#user-${data.user_id}`);
    handlePeerDisconnect();
    if(userImage !== undefined){
      userImage.remove();
    }
  })

  function startApp(){
    getChannels().then(channels =>{
      for (const channel of channels) {
          createChannel(channel);

          document.getElementById(channel.id).addEventListener('click', ()=>{
            if(running) return;
            running = true;
            if(currentVoice !== undefined){
              document.getElementById(currentVoice).classList.remove('voice-active');
              socket.emit('voice-disconnect',{
                channel_id: currentVoice,
                user_id: user_id,
                guild_id: guild_id
              })
            }

            const element = document.getElementById(channel.id);
            currentVoice = channel.id;
            element.classList.add('voice-active');
            socket.emit("voice-connect", {
              channel_id: currentVoice,
              user_id: user_id,
              guild_id: guild_id
            });
            running = false;
          });
      }
    })
  }
  function createChannel(channel){
    $('#channels').append(`
      <li class="text-gray-500 px-2 mb-3 pt-1 hover:text-gray-200 hover:bg-gray-900" name="voiceChannel" id="${channel.id}">
        <a class="flex items-center">
          <span class="text-xl"><i class='bx bxs-volume-full'></i></span>
          <span class="ml-2">${channel.name}</span>
          <div>
            <img class="rounded-full w-4 h-4" alt="">
          </div>
        </a>
      </li>
    `);
  }

  function createUser(data){
    const user = document.getElementById('user-' + data.user_id);
    if(user === null && data.channel_id !== undefined){
      getUser(data.user_id).then(user =>{
        $('#users').append(`
          <li class="py-3 sm:py-4" id="user-${user.id}">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <img class="w-8 h-8 rounded-full" src="${home}${phpPort}/images/${user.id}.${user.image_format}" alt="Profile image">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                        ${user.username}
                    </p>
                </div>
            </div>
          </li>
        `);
      });
    }
  }


  function getChannels(){
    return new Promise((resolve, reject) =>{
      fetch(`${home}${phpPort}/guild/getChannels`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          guild_id: guild_id,
          user_id: user_id
        })
      }).then(response => response.json()).then(data => {
        resolve(data);
      })
    })
  }
  function getUser(user_id){
    return new Promise((resolve, reject) =>{
      fetch(`${home}${phpPort}/user/${user_id}`).then(response => response.json()).then(data => {
        resolve(data);
      })
    });
  }


  function handlePeerDisconnect() {
    for (let conns in peer.connections) {
      peer.connections[conns].forEach((conn, index, array) => {
        conn.peerConnection.close();
        if (conn.close)
          conn.close();
      });
    }
  }

  function handlePeerConnection(data) {

  }
})()

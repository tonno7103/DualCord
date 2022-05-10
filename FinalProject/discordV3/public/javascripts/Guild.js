(()=>{

  socket.emit('guild-connect', {
    guild_id: guild_id,
    user_id: user_id,
  });



  socket.on('all-users-connected', (data)=>{
    console.log('all users connected');
    console.log(data);
  });
  startApp();
  function startApp(){
    getChannels().then(channels =>{
      for (const channel of channels) {
          createChannel(channel);
      }
    })
  }

  function createChannel(channel){
    $('#channels').append(`
      <li class="text-gray-500 px-2 mb-3 pt-1 hover:text-gray-200 hover:bg-gray-900" id="channel-${channel.id}">
        <a class="flex items-center">
          <span class="text-xl"><i class='bx bxs-volume-full'></i></span>
          <span class="ml-2">${channel.name}</span>
          <div class="items-end" id="channel-image-${channel.id}"></div>
        </a>
      </li>
    `);
    $(`#channel-${channel.id}`).click(()=>{
      location.replace(`${home}${nodePort}/guild/${guild_id}/voice/${channel.id}`);
    });
  }


  /* api */
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
})();

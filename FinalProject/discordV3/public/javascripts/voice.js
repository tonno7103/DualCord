(() =>{
    let USE_AUDIO = true;
    let USE_VIDEO = false;
    let MUTE_AUDIO_BY_DEFAULT = false;
    let peer_media_elements = {};
    let local_media_stream = null;

    let peers = {};

    socket.on('channel-created', (data)=>{
        console.log('Channel created', data);
        createChannel(data);
    });

    setup_local_media(function() {
        socket.emit("voice-connect", {
            channel_id: voice_id,
            user_id: user_id,
            guild_id: guild_id,
        });
    });

    let iceServers = [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' },
        { urls: 'stun:stun2.l.google.com:19302' },
        { urls: 'stun:stun3.l.google.com:19302' },
        { urls: 'stun:stun4.l.google.com:19302' },
    ];

    document.getElementById('mute').addEventListener('click', () => {
        if (local_media_stream) {
            local_media_stream.getAudioTracks()[0].enabled = !local_media_stream.getAudioTracks()[0].enabled;
            if(document.getElementById('muteButton').classList.contains('bxs-microphone-off')) {
                document.getElementById('muteButton').classList.replace('bxs-microphone-off', 'bxs-microphone');
            } else {
                document.getElementById('muteButton').classList.replace('bxs-microphone', 'bxs-microphone-off');
            }
        }
    });

    startApp();
    getCurrentsMembers();


    function startApp(){
        getChannels().then(channels =>{
            for (const channel of channels) {
                createChannel(channel);
            }
        })
    }

    function createChannel(channel){
        $('#channels').append(`
          <li class="text-gray-500 px-2 mb-3 pt-1 hover:text-gray-200 hover:bg-gray-900 ${channel.id === voice_id ? 'voice-active' : ''}" id="channel-${channel.id}">
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
    function createUser(data){
        getUser(data.user_id).then(user =>{
            $(`#users`).append(`
            <li class="py-3 sm:py-4" id="user-${data.user_id}">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="w-8 h-8 rounded-full" onerror="setDefaultImage(this)" src="${home}${phpPort}/images/${data.user_id}.${user.image_format}" alt="">
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

    function removeUser(data){
        $(`#user-${data.user_id}`).remove();
    }

    /* Api */
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
        return new Promise((resolve, reject) => {
            fetch(`${home}${phpPort}/user/` + user_id).then(response => response.json()).then(data => {
                resolve(data);
            })
        });
    }


    /* Socket */
    function getCurrentsMembers(){
        socket.emit("voice-getCurrents-members", {
            channel_id: voice_id,
            user_id: user_id
        });
    }
    /* call */

    socket.on('addPeer', function(config) {
        console.log('Signaling server said to add peer:', config);
        const peer_id = config.peer_id;
        if (peer_id in peers) {
            console.log("Already connected to peer ", peer_id);
            return;
        }
        const peer_connection = new RTCPeerConnection(
            {"iceServers": iceServers},
        );
        peers[peer_id] = peer_connection;

        peer_connection.onicecandidate = function(event) {
            if (event.candidate) {
                socket.emit('relayICECandidate', {
                    'peer_id': peer_id,
                    'ice_candidate': {
                        'sdpMLineIndex': event.candidate.sdpMLineIndex,
                        'candidate': event.candidate.candidate
                    }
                });
            }
        }
        peer_connection.ontrack = function(event) {
            console.log("ontrack", event);
            const remote_media = USE_VIDEO ? $("<video>") : $("<audio>");
            remote_media.attr("autoplay", "autoplay");
            if (MUTE_AUDIO_BY_DEFAULT) {
                remote_media.attr("muted", "true");
            }
            remote_media.attr("controls", "");
            peer_media_elements[peer_id] = remote_media;
            $('body').append(remote_media);
            attachMediaStream(remote_media[0], event.streams[0]);
        }

        /* Add our local stream */
        peer_connection.addStream(local_media_stream);

        if (config.should_create_offer) {
            console.log("Creating RTC offer to ", peer_id);
            peer_connection.createOffer(
                function (local_description) {
                    console.log("Local offer description is: ", local_description);
                    peer_connection.setLocalDescription(local_description,
                        function() {
                            socket.emit('relaySessionDescription',
                                {'peer_id': peer_id, 'session_description': local_description});
                            console.log("Offer setLocalDescription succeeded");
                        },
                        function() { Alert("Offer setLocalDescription failed!"); }
                    );
                },
                function (error) {
                    console.log("Error sending offer: ", error);
                });
        }
    });

    function setup_local_media(callback, errorback) {
        if (local_media_stream != null) {
            if (callback) callback();
            return;
        }

        console.log("Requesting access to local audio / video inputs");


        navigator.getUserMedia = ( navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

        attachMediaStream = function (element, stream) {
            console.log('DEPRECATED, attachMediaStream will soon be removed.');
            element.srcObject = stream;
        };

        navigator.mediaDevices.getUserMedia({"audio":USE_AUDIO, "video":USE_VIDEO})
            .then(function(stream) {
                console.log("Access granted to audio/video");
                local_media_stream = stream;
                var local_media = USE_VIDEO ? $("<video>") : $("<audio>");
                local_media.attr("autoplay", "autoplay");
                local_media.attr("muted", "true");
                local_media.attr("controls", "");
                document.getElementById('muteButton').classList.replace('bxs-microphone-off', 'bxs-microphone');
                $('body').append(local_media);

                if (callback) callback();
            })
            .catch(function() {
                console.log("Access denied for audio/video");
                alert("You chose not to provide access to the camera/microphone, demo will not work.");
                if (errorback) errorback();
            })
    }


    /* Call Events */


    socket.on("voice-connected", async (data) => {
        createUser(data);
    });

    socket.on('iceCandidate', function(config) {
        let peer = peers[config.peer_id];
        let ice_candidate = config.ice_candidate;
        peer.addIceCandidate(new RTCIceCandidate(ice_candidate));
    });

    socket.on('removePeer', function(config) {
        console.log('Signaling server said to remove peer:', config);
        let peer_id = config.peer_id;
        if (peer_id in peer_media_elements) {
            peer_media_elements[peer_id].remove();
        }
        if (peer_id in peers) {
            peers[peer_id].close();
        }

        delete peers[peer_id];
        delete peer_media_elements[config.peer_id];
    });
    socket.on('sessionDescription', function(config) {
        console.log('Remote description received: ', config);
        let peer_id = config.peer_id;
        let peer = peers[peer_id];
        let remote_description = config.session_description;
        console.log(config.session_description);

        let desc = new RTCSessionDescription(remote_description);
        let stuff = peer.setRemoteDescription(desc,
            function() {
                console.log("setRemoteDescription succeeded");
                if (remote_description.type === "offer") {
                    console.log("Creating answer");
                    peer.createAnswer(
                        function(local_description) {
                            console.log("Answer description is: ", local_description);
                            peer.setLocalDescription(local_description,
                                function() {
                                    socket.emit('relaySessionDescription',
                                        {'peer_id': peer_id, 'session_description': local_description});
                                    console.log("Answer setLocalDescription succeeded");
                                },
                                function() { Alert("Answer setLocalDescription failed!"); }
                            );
                        },
                        function(error) {
                            console.log("Error creating answer: ", error);
                            console.log(peer);
                        });
                }
            },
            function(error) {
                console.log("setRemoteDescription error: ", error);
            }
        );
        console.log("Description Object: ", desc);

    });

    socket.on("voice-disconnected", (data) => {
        removeUser(data);
    });

    socket.on('voice-current-members-result', (data) => {
        createUser(data);
    });

})();
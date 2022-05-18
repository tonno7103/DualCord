const express = require('express');
const path = require('path');
const proxy = require('express-http-proxy');
const { Server } = require("socket.io");
const app = express();
const http = require('http').Server(app);
const loader = require("./Scripts/loader");
loader.loadCommands();
loader.loadBluePrints(app);

/* view engine setup */
app.engine('ejs', require('express-ejs-extend'));
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');


app.use("/l", proxy(`127.0.0.1:8081`, {}));

app.use(express.static(path.join(__dirname, 'public')));

http.listen(8080, ()=>{
  console.log("Server started at http://localhost:8080")
});

const io = new Server(http, {
  pingInterval: 10000,
  pingTimeout: 5000,
  cookie: false
});

let channels = {};
let sockets = {};


io.on('connection', (socket) => {
  socket.channels = {};
  sockets[socket.id] = socket;

  socket.on('voice-connect', (data) => {
    let channel = data.channel_id;
    let userdata = data.user_id;

    if (channel in socket.channels) {
      console.log("["+ socket.id + "] ERROR: already joined ", channel);
      return;
    }
    if (!(channel in channels)) {
      channels[channel] = {};
    }
    for (let id in channels[channel]) {
      channels[channel][id].emit('addPeer', {'peer_id': socket.id, 'should_create_offer': false});
      socket.emit('addPeer', {'peer_id': id, 'should_create_offer': true});
    }

    socket.join('user-' + data.user_id)
    socket.join("voice-" + data.channel_id);
    socket.join('guild-' + data.guild_id);
    socket.channel_id = data.channel_id;
    socket.user_id = data.user_id;

    console.log(`[Socket] user: ${data.user_id} joined to voice: ${data.channel_id}`);

    socket.to('voice-' + data.channel_id).emit("voice-connected", data);

    channels[channel][socket.id] = socket;
    socket.channels[channel] = channel;
  });


  function part(channel) {
    console.log("["+ socket.id + "] part ");

    if (!(channel in socket.channels)) {
      console.log("["+ socket.id + "] ERROR: not in ", channel);
      return;
    }

    delete socket.channels[channel];
    delete channels[channel][socket.id];

    for (let id in channels[channel]) {
      channels[channel][id].emit('removePeer', {'peer_id': socket.id});
      socket.emit('removePeer', {'peer_id': id});
    }
  }
  socket.on('part', part);
  socket.on('relayICECandidate', function(config) {
    let peer_id = config.peer_id;
    let ice_candidate = config.ice_candidate;
    console.log("["+ socket.id + "] relaying ICE candidate to [" + peer_id + "] ", ice_candidate);

    if (peer_id in sockets) {
      sockets[peer_id].emit('iceCandidate', {'peer_id': socket.id, 'ice_candidate': ice_candidate});
    }
  });
  socket.on('relaySessionDescription', function(config) {
    let peer_id = config.peer_id;
    let session_description = config.session_description;
    console.log("["+ socket.id + "] relaying session description to [" + peer_id + "] ", session_description);

    if (peer_id in sockets) {
      sockets[peer_id].emit('sessionDescription', {'peer_id': socket.id, 'session_description': session_description});
    }
  });


  socket.on('guild-connect', (data) => {
    socket.user_id = data.user_id;
    socket.guild_id = data.guild_id;
    socket.join('user-' + data.user_id);


    socket.join("guild-" + data.guild_id);
    console.log(`[Socket] user: ${data.user_id} joined to guild: ${data.guild_id}`);
  });

  socket.on('voice-getCurrents-members', (data) => {
    console.log('[Socket] voice-getCurrents-members');
    socket.join(socket.user_id);
    io.sockets.sockets.forEach(socketUser => {
      if(socketUser.user_id !== data.user_id && socketUser.channel_id === data.channel_id){
        const response = {
          user_id: socketUser.user_id,
          channel_id: socketUser.channel_id
        }
        io.to(socket.user_id).emit("voice-current-members-result", response);
      }
    });
    socket.leave(socket.user_id);
  });



  socket.on('create-channel', (data) => {
    io.to('guild-' + data.guild_id).emit('channel-created', data);
  });

  socket.on('disconnecting', () => {
    console.log(`[Socket] user: ${socket.user_id} left to guild: ${socket.guild_id}`);
    io.to("guild-" + socket.guild_id).emit('user-disconnected', {user_id: socket.user_id});
    io.to("voice-" + socket.channel_id).emit('voice-disconnected', {user_id: socket.user_id});
    socket.leave(socket.rooms);
    for (const channel in socket.channels) {
      part(channel);
    }
    delete sockets[socket.id];
  });

  socket.on('kick', (data) => {
    console.log('[Socket] kick');
    io.to("user-" + data.user_id).emit('receiver-kick');
  });

  socket.on('ban', (data) => {
    console.log('[Socket] ban');
    io.to("user-" + data.user_id).emit('receiver-ban');
  });
});




module.exports = app;
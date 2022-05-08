const express = require('express');
const path = require('path');
const proxy = require('express-http-proxy');
const {phpPort} = require('./utils/path.json')
const { Server } = require("socket.io");
const { ExpressPeerServer } = require('peer');
const app = express();
const http = require('http').Server(app);
const peerServer = ExpressPeerServer(http, {
    debug: true
});
const loader = require("./Scripts/loader");
loader.loadCommands();
loader.loadBluePrints(app);

/* view engine setup */
app.engine('ejs', require('express-ejs-extend'));
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');


app.use("/l", proxy(`127.0.0.1:8081`, {}));

app.use('/peerjs', peerServer);
app.use(express.static(path.join(__dirname, 'public')));

http.listen(8080, ()=>{
  console.log("Server started at http://localhost:8080")
});

const io = new Server(http, {
  pingInterval: 10000,
  pingTimeout: 5000,
  cookie: false
});


io.on('connection', (socket) => {
  socket.on('voice-connect', (data) => {
    data = Object.assign({
      peer_id: socket.peer_id
    }, data);

    socket.join("voice-" + data.channel_id);
    socket.channel_id = data.channel_id;
    console.log(`[Socket] user: ${data.user_id} joined to voice: ${data.channel_id}`);
    io.to("guild-" + data.guild_id).emit("user-connected", data);
    socket.to('voice-' + data.channel_id).emit("voice-connected", data);
  });

  socket.on('voice-disconnect', (data) => {
    data = Object.assign({
      peer_id: socket.peer_id
    }, data);

    socket.leave('voice-' + data.channel_id);
    socket.channel_id = undefined;
    console.log(`[Socket] user: ${data.user_id} left to voice: ${data.channel_id}`);
    io.to("guild-" + data.guild_id).emit('user-disconnected', data);
  });

  socket.on('guild-connect', (data) => {
    socket.user_id = data.user_id;
    socket.guild_id = data.guild_id;
    socket.peer_id = data.peer_id;

    socket.join("guild-" + data.guild_id);
    console.log(`[Socket] user: ${data.user_id} joined to guild: ${data.guild_id}`);

    io.sockets.sockets.forEach((socketClient) => {
      if(socketClient.user_id !== data.user_id && socketClient.guild_id === data.guild_id) {
        const dataSocket = {
          user_id: socketClient.user_id,
          guild_id: socketClient.guild_id,
          channel_id: socketClient.channel_id,
          peer_id: socketClient.peer_id
        };

        console.log('[Socket] user: ' + dataSocket.user_id + ' is inside a channel: ' + dataSocket.guild_id);
        io.to("guild-" + dataSocket.guild_id).emit('user-connected', dataSocket);
      }
    })
  });



  socket.on('disconnecting', () => {
    console.log(`[Socket] user: ${socket.user_id} left to guild: ${socket.guild_id}`);
    io.to("guild-" + socket.guild_id).emit('user-disconnected', {user_id: socket.user_id});
    socket.leave(socket.rooms);
  });
});




module.exports = app;

const express = require('express');
const path = require('path');
const proxy = require('express-http-proxy');
const {phpPort} = require('./utils/path.json')



const app = express();
const loader = require("./Scripts/loader");
loader.loadCommands();
loader.loadBluePrints(app);

/* view engine setup */
app.engine('ejs', require('express-ejs-extend'));
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');


app.use("/l", proxy(`127.0.0.1:8081`, {}));


app.use(express.static(path.join(__dirname, 'public')));

app.listen(8080, ()=>{
  console.log("Server started at http://localhost:8080")
})

module.exports = app;

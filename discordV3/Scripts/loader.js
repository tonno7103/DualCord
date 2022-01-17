/* Load all commands for handlebars files */
function loadCommands(){
    const Handlebars = require("handlebars");

    Handlebars.registerHelper('for', (from, to, incr, block) => {
        let accum = '';
        for(let i = from; i < to; i += incr)
            accum += block.fn(i);
        return accum;
    });  

    console.log("[OUTPUT] Utils: Commands loaded")
}

/* Load all bluePrints into routes folder */

function loadBluePrints(app){
    const fs = require('fs');

    const files = fs.readdirSync("./routes/");
    files.forEach((file)=>{
        const router = require("../routes/" + file);
        app.use(router);
    })
    console.log("[OUTPUT] Utils: BluePrints loaded")
}


/* Session loading */

function loadSession(app){
    const session = require('express-session');

    const crypto = require('crypto');
    const token = crypto.randomBytes(16).toString('hex');
    const expiryDate = new Date(Date.now() + (60 * 60 * 1000 * 24 * 7));

    app.set('trust proxy', 1)
    app.use(session({
        secret: token,
        name: 'Session_id',
        proxy: true,
        resave: false,
        saveUninitialized: true,
        cookie: { 
            httpOnly: true,
            expires: expiryDate,
            Secure: true,
        }    
    }));

    console.log(`[SESSION] Token: ${token}. Session started!`)
}

module.exports = {loadCommands,loadBluePrints,loadSession}
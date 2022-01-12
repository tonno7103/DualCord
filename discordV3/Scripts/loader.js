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

function loadBluePrints(app){
    const fs = require('fs');

    const files = fs.readdirSync("./routes/");
    files.forEach((file)=>{
        const router = require("../routes/" + file);
        app.use(router);
    })
    console.log("[OUTPUT] Utils: BluePrints loaded")
}

module.exports = {
    loadCommands,
    loadBluePrints
}
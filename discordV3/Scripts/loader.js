/* Load all commands for handlebars files */
function loadCommands(){
    const Handlebars = require("handlebars");

    Handlebars.registerHelper('for', (from, to, incr, block) => {
        let accum = '';
        for(let i = from; i < to; i += incr)
            accum += block.fn(i);
        return accum;
    });  

    Handlebars.registerHelper('ifCond', (v1, operator, v2, options) => {

        switch (operator) {
            case '==':
                return (v1 == v2) ? options.fn(this) : options.inverse(this);
            case '===':
                return (v1 === v2) ? options.fn(this) : options.inverse(this);
            case '!=':
                return (v1 != v2) ? options.fn(this) : options.inverse(this);
            case '!==':
                return (v1 !== v2) ? options.fn(this) : options.inverse(this);
            case '<':
                return (v1 < v2) ? options.fn(this) : options.inverse(this);
            case '<=':
                return (v1 <= v2) ? options.fn(this) : options.inverse(this);
            case '>':
                return (v1 > v2) ? options.fn(this) : options.inverse(this);
            case '>=':
                return (v1 >= v2) ? options.fn(this) : options.inverse(this);
            case '&&':
                return (v1 && v2) ? options.fn(this) : options.inverse(this);
            case '||':
                return (v1 || v2) ? options.fn(this) : options.inverse(this);
            default:
                return options.inverse(this);
        }
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


module.exports = {loadCommands,loadBluePrints}
var express = require('express');
var router = express.Router();
const phpsession = require('../Scripts/Phpsession')
const bodyParser = require('body-parser');
const cookie = require("body-parser/lib/types/json");
const urlencodedParser = bodyParser.urlencoded({ extended: false })


router.post('/login', urlencodedParser,  function (req, res, next) {
    console.log("[Users.JS] Request received")
    console.log("[Users.JS] Request body: " + JSON.stringify(req.body))
    phpsession.writeData(req.body['key'], req.body['user_id']).then(() => {
        console.log("[Users.JS] Data written");
        return res.send({"success": true});
    }).catch(() => {
        console.log("[Users.JS] Data not written");
        return res.send({"success": false});
    });
});

router.post('/logout', urlencodedParser,  function (req, res, next) {
    console.log("[Users.JS] Request received")
    console.log("[Users.JS] Request body: " + JSON.stringify(req.body))
    phpsession.deleteData(req.body['key']).then(() => {
        console.log("[Users.JS] Data deleted");
        return res.send({"success": true});
    }).catch(() => {
        console.log("[Users.JS] Data not deleted");
        return res.send({"success": false});
    });
});

module.exports = router;

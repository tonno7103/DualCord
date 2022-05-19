const router = require('express').Router();
const gifResize = require('@gumlet/gif-resize');
const fs = require("fs");
const bodyParser = require("body-parser");
const urlencodedParser = bodyParser.urlencoded({ extended: false })


router.post('/gif/resize', urlencodedParser, async (req, res) => {
    console.log(JSON.stringify(req.body));
    const gif = fs.readFileSync("../PhpSide/public/images/" + req.body['image_name']);
    gifResize({
        width: 128
    })(gif).then(data => {
        fs.writeFileSync("../PhpSide/public/images/" + req.body['image_name'], data);
        res.send(data);
    });
});


module.exports = router;
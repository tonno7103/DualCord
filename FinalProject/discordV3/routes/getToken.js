const express = require('express');
const phpsession = require("../Scripts/Phpsession");
const db = require("../Scripts/Query");
const router = express.Router();

router.post("/getToken", (req, res, next) => {
    console.log("[GETTOKEN.JS] Request received")
    phpsession.getData("PHPSESSID", req)
        .then(data=>{
            return res.send({"token": data})
        })
        .catch(err=>{
            return res.send({"token": "error"})
        })
});


module.exports = router;
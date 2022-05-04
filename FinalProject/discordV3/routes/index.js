const express = require('express');
const phpsession = require("../Scripts/Phpsession");
const db = require("../Scripts/Query");
const router = express.Router();


router.get("/", (req, res, next) => {
    const {address, nodePort, phpPort} = require('../utils/path.json');
    const { imagesLogo } = require("../utils/database.json")

    phpsession.getData("PHPSESSID", req)
        .then(data=>{
            console.log("[INDEX.JS] Result of scanning:", data)
            db.getValue(data, "username, image_format")
                .then(result=>{

                    const username = result[0].username
                    const image_format = result[0].image_format
                    const image = `${address}${phpPort}${imagesLogo}/${data}.${image_format}`
                    let infos = {
                        username: username,
                        imagePath: image
                    }
                    return res.render("index", {
                        home: `${address}`,
                        nodePort: `${nodePort}`,
                        phpPort: `${phpPort}`,
                        title: "DualCord",
                        username: infos.username,
                        imagePath: infos.imagePath
                    })
                })
                .catch(err=>{
                    console.log(err)
                    return res.render("index", {
                        home: `${address}`,
                        nodePort: `${nodePort}`,
                        phpPort: `${phpPort}`,
                        title: "DualCord",
                    })
                })
        })
        .catch(err=>{
            console.log(err)
            return res.render("index", {
                home: `${address}`,
                nodePort: `${nodePort}`,
                phpPort: `${phpPort}`,
                title: "DualCord",
            })
        });
});

module.exports = router;
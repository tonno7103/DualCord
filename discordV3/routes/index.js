const express = require('express');
const router = express.Router();

router.get("/", (req, res, next) => {
    const {address} = require('../utils/path.json');    
    res.render("index", {
        home: `${address}/home`,
        title: "Steful", 
    })
});

module.exports = router;
const express = require('express');
const router = express.Router();

router.get("/", (req, res, next) => {
    res.render("index", {
        home: "http://localhost/home",
        title: "Steful", 
    })
});

module.exports = router;
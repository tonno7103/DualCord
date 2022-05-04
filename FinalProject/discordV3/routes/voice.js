const router = require("express").Router();

router.get('/voice', (req, res) => {
    const {address, nodePort, phpPort} = require('../utils/path.json');
    return res.render('voice', {
        title: 'Voice',
        user: req.user,
        home: address,
        nodePort,
        phpPort,
        guild: {
            name: "sus"
        }
    });
});


module.exports = router;
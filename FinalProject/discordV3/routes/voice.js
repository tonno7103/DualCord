const phpSession = require("../Scripts/Phpsession");
const {address, phpPort, nodePort} = require("../utils/path.json");
const router = require("express").Router();
const axios = require("axios");

router.get('/guilds', (req, res) => {
    const {address, nodePort, phpPort} = require('../utils/path.json');
    return res.render('guilds', {
        title: 'Guilds',
        home: address,
        nodePort,
        phpPort,
    });
});


router.get('/guild/:id', (req, res) => {
    const {id} = req.params;

    phpSession.getData("PHPSESSID", req)
        .then(data=> {
            getUser(data).then(user => {
                console.log("[VOICE.JS] Result of scanning:", data);
                axios.post(`${address}${phpPort}/user/have-access/guild/` + id, {'user_id': data})
                    .then(response => {
                        if(response.data.access){
                            return res.render('guild', {
                                title: 'Guild',
                                home: address,
                                nodePort,
                                phpPort,
                                guild_id: id,
                                user_id: data,
                                user_image_format: user.image_format,
                                user_username: user.username,
                            });
                        }
                        else{
                            return res.redirect(`${address}${nodePort}/guilds`);
                        }
                    })
                    .catch(error => {
                        console.log("[VOICE.JS] post error:", error);
                        return res.send(error);
                    });
            });

        })
        .catch(err=> {
            console.log("[VOICE.JS] getting PHP data error:", err);
            return res.redirect(`${address}${nodePort}`);
        });
});


router.get('/guild/:guild_id/voice/:voice_id', async (req, res) => {
    const {guild_id, voice_id} = req.params;

    phpSession.getData("PHPSESSID", req)
        .then(data=> {
            getUser(data).then(user => {
                console.log("[VOICE.JS] Result of scanning:", data);
                haveAccess(data, voice_id).then(access => {
                    console.log("[VOICE.JS] Result of access:", access);
                    if(access){
                       return res.render('voice', {
                           title: 'Voice',
                           home: address,
                           nodePort,
                           phpPort,
                           guild_id,
                           voice_id,
                           user_id: data,
                           user_image_format: user.image_format,
                           user_username: user.username,
                       });
                    } else{
                        return res.redirect(`${address}${nodePort}/guild/${guild_id}`);
                    }
                });
            });
        })
        .catch(err=> {
            return res.redirect(`${address}${nodePort}`);
        });
});

function getUser(user_id){
    return new Promise((resolve, reject) =>{
        axios.get(`${address}${phpPort}/user/${user_id}`).then(response => {
            resolve(response.data);
        })
    });
}
function haveAccess(user_id, voice_id){
    return new Promise((resolve, reject) =>{
        axios.post(`${address}${phpPort}/user/have-access/voice/` + voice_id, {
            'user_id': user_id,
        })
        .then(response => {
            resolve(response.data.access);
        });
    });
}
module.exports = router;
window.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    document.getElementById("contextMenu").style.display = "block";
    document.getElementById("contextMenu").style.left = e.clientX + "px";
    document.getElementById("contextMenu").style.top = e.clientY + "px";
});
document.getElementById("contextMenu").addEventListener("click", function(e) {
    e.preventDefault();
    document.getElementById("contextMenu").style.display = "none";
});
document.getElementById("contextMenu").addEventListener("contextmenu", function(e) {
    e.preventDefault();
    document.getElementById("contextMenu").style.display = "none";
});
window.addEventListener('click', function(e) {
    if(e.target.id !== "contextMenu") {
        document.getElementById("contextMenu").style.display = "none";
    }
});
document.addEventListener('keydown', function(e) {
    if(e.keyCode === 27) {
        document.getElementById("contextMenu").style.display = "none";
    }
});

let runningKick = false;
let runningBan = false;
let runningUnban = false;

if(document.getElementById('open-kickModal') !== null)
    document.getElementById('open-kickModal').addEventListener('click', function() {
        document.getElementById('kickUser').value = '';
        document.getElementById('kickUser').addEventListener('input', function() {
            const username = document.getElementById('kickUser').value;
            if(username.length === 0) {
                getLowerLevel()
                    .then(data =>{
                        clearChildren(document.getElementById('kick-users'));
                        const members = data.members;
                        setKickLowerLevel(members);
                    })
            } else {
                if(runningKick) return;
                runningKick = true;
                getUsersLowerLevel(username)
                    .then(users => {
                        clearChildren(document.getElementById('kick-users'));
                        setKickLowerLevel(users.users);
                        runningKick = false;
                    })
                    .catch(err => {
                        runningKick = false;
                    });
            }
        });
        clearChildren(document.getElementById('kick-users'));
        getLowerLevel()
            .then(data =>{
                const members = data.members;
                setKickLowerLevel(members);
            })
    });


if(document.getElementById('open-banModal') !== null)
    document.getElementById('open-banModal').addEventListener('click', function() {
        document.getElementById('banUser').value = '';
        document.getElementById('banUser').addEventListener('input', function() {
            const username = document.getElementById('banUser').value;
            if(username.length === 0) {
                getLowerLevel()
                    .then(data =>{
                        clearChildren(document.getElementById('ban-users'));
                        const members = data.members;
                        setBanLowerLevel(members);
                    })
            } else {
                if(runningBan) return;
                runningBan = true;
                getUsersLowerLevel(username)
                    .then(users => {
                        clearChildren(document.getElementById('ban-users'));
                        setBanLowerLevel(users.users);
                        runningBan = false;
                    })
                    .catch(err => {
                        runningBan = false;
                    });
            }
        });
        clearChildren(document.getElementById('ban-users'));
        getLowerLevel()
            .then(data =>{
                const members = data.members;
                setBanLowerLevel(members);
            })

    });

if(document.getElementById('open-unbanModal') !== null)
    document.getElementById('open-unbanModal').addEventListener('click', function (){
        clearChildren(document.getElementById('unban-users'));
        document.getElementById('unbanUser').value = '';
        getBannedUsers()
            .then(users => {
                setUnbanLowerLevel(users.users);
            })
        document.getElementById('unbanUser').addEventListener('input', function() {
            const username = document.getElementById('unbanUser').value;
            if(username.length === 0) {
                getBannedUsers()
                    .then(users => {
                        clearChildren(document.getElementById('unban-users'));
                        setUnbanLowerLevel(users.users);
                    })
            } else {
                if(runningUnban) return;
                runningUnban = true;
                getUsersLowerLevelBanned(username)
                    .then(users => {
                        clearChildren(document.getElementById('unban-users'));
                        setUnbanLowerLevel(users.users);
                        runningUnban = false;
                    })
                    .catch(err => {
                        runningUnban = false;
                    });
            }
        });
    });



function setKickLowerLevel(members){
    for (const member of members) {
        $(`#kick-users`).append(`
             <li>
                <a id="kick-user-${member.user_id}" class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg hover:bg-gray-100 group hover:shadow dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white">
                    <img class="h-8 rounded-full" src="${home}${phpPort}/images/${member.user_id}.${member.image_format}" alt="" onerror="setDefaultImage(this)">
                    <span class="flex-1 ml-3" style="overflow-wrap: anywhere">${member.username}</span>
                </a>
            </li>
        `);

        document.getElementById(`kick-user-${member.user_id}`).addEventListener('mouseenter', function(e) {
            $('#kick-user-' + member.user_id).append(`
                <button type="button" id="kick-button" style="border-color: #9B1C1C !important" class="text-red-700 hover:text-white border-red-700 border hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">Kick user</button>
            `);
            document.getElementById('kick-button').addEventListener('click', function(e) {
                e.preventDefault();
                kickUser(member.user_id).then(data => {
                    if(data.success) {
                        socket.emit('kick', {
                            guild_id: guild_id,
                            user_id: member.user_id
                        });
                        document.getElementById(`kick-user-${member.user_id}`).remove();
                    }
                });
            });
        });
        document.getElementById(`kick-user-${member.user_id}`).addEventListener('mouseleave', function(e) {
            $('#kick-button').remove();
        });

    }
}
function setBanLowerLevel(members){
    for (const member of members) {
        $(`#ban-users`).append(`
             <li>
                <a id="ban-user-${member.user_id}" class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg hover:bg-gray-100 group hover:shadow dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white">
                    <img class="h-8 rounded-full" src="${home}${phpPort}/images/${member.user_id}.${member.image_format}" alt="" onerror="setDefaultImage(this)">
                    <span class="flex-1 ml-3" style="overflow-wrap: anywhere">${member.username}</span>
                </a>
            </li>
        `);

        document.getElementById(`ban-user-${member.user_id}`).addEventListener('mouseenter', function(e) {
            $('#ban-user-' + member.user_id).append(`
                <button type="button" id="ban-button" style="border-color: #9B1C1C !important" class="text-red-700 hover:text-white border-red-700 border hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">Ban user</button>
            `);
            document.getElementById('ban-button').addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Ban user');
                banUser(member.user_id).then(data => {
                    if(data.success) {
                        socket.emit('ban', {
                            guild_id: guild_id,
                            user_id: member.user_id
                        });
                        document.getElementById(`ban-user-${member.user_id}`).remove();
                    }
                });
            });
        });
        document.getElementById(`ban-user-${member.user_id}`).addEventListener('mouseleave', function(e) {
            $('#ban-button').remove();
        });

    }
}
function setUnbanLowerLevel(members){
    for (const member of members) {
        $(`#unban-users`).append(`
             <li>
                <a id="unban-user-${member.user_id}" class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg hover:bg-gray-100 group hover:shadow dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white">
                    <img class="h-8 rounded-full" src="${home}${phpPort}/images/${member.user_id}.${member.image_format}" alt="" onerror="setDefaultImage(this)">
                    <span class="flex-1 ml-3" style="overflow-wrap: anywhere">${member.username}</span>
                </a>
            </li>
        `);

        document.getElementById(`unban-user-${member.user_id}`).addEventListener('mouseenter', function (e) {
            $('#unban-user-' + member.user_id).append(`
                <button type="button" id="unban-button" style="border-color: #31C48D !important" class="text-green-400 hover:text-white border-green-400 border hover:bg-green-400 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-2 py-2 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-900">Unban user</button>
            `);
            document.getElementById('unban-button').addEventListener('click', function (e) {
                e.preventDefault();
                unbanUser(member.user_id).then(data => {
                    if (data.success) {
                        document.getElementById(`unban-user-${member.user_id}`).remove();
                    }
                });
                console.log('Unban user');
            });
        });
        document.getElementById(`unban-user-${member.user_id}`).addEventListener('mouseleave', function(e) {
            $('#unban-button').remove();
        });
    }
}

function clearChildren(element) {
    while(element.firstChild) {
        element.removeChild(element.firstChild);
    }
}

/* Api */

function getLowerLevel(){
    return new Promise(function(resolve, reject) {
        fetch(`${home}${phpPort}/guild/getLowerPower/${guild_id}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                resolve(res);
            } else {
                reject(res);
            }
        })
    });
}
function getBannedUsers(){
    return new Promise(function(resolve, reject) {
        fetch(`${home}${phpPort}/guild/getBannedUsers/${guild_id}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                resolve(res);
            } else {
                reject(res);
            }
        })
    });
}


function kickUser(target_id) {
    return new Promise(function(resolve, reject) {
        fetch(`${home}${phpPort}/guild/kickUser/${guild_id}/${target_id}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                resolve(res);
            } else {
                reject(res);
            }
        })
    });
}
function banUser(target_id) {
    return new Promise(function(resolve, reject) {
        fetch(`${home}${phpPort}/guild/banUser/${guild_id}/${target_id}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                resolve(res);
            } else {
                reject(res);
            }
        })
    });
}
function unbanUser(target_id) {
    return new Promise(function(resolve, reject) {
        fetch(`${home}${phpPort}/guild/unbanUser/${guild_id}/${target_id}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                resolve(res);
            } else {
                reject(res);
            }
        })
    });
}

function getUsersLowerLevel(username){
    return new Promise(function(resolve, reject) {
        fetch(`${home}${phpPort}/guild/getUsersLowerLevel/${guild_id}/${username}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                resolve(res);
            } else {
                reject(res);
            }
        })
    });
}
function getUsersLowerLevelBanned(username){
    return new Promise(function(resolve, reject) {
        fetch(`${home}${phpPort}/guild/getUsersLowerLevelBanned/${guild_id}/${username}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                resolve(res);
            } else {
                reject(res);
            }
        })
    });
}


socket.on('receiver-kick', function(data) {
    window.location.replace(`${home}${nodePort}/guilds`);
});

socket.on('receiver-ban', function(data) {
    window.location.replace(`${home}${nodePort}/guilds`);
});



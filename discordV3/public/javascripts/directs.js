async function get_user_token(url){
    return new Promise((resolve, rejected) =>{
        fetch(url, {
            method: 'POST',
            body: JSON.stringify({
                "sesson_name": "PHPSESSID"
            })
        })
        .then(res => res.json())
        .then(res => {
            resolve(res.token);
        });
    });
}
async function removeMessages(){
    const messages = document.getElementById("messages");
    while(messages.firstChild){
        messages.removeChild(messages.firstChild);
    }
}

async function removeAllChilderns(element){
    while(element.firstChild){
        element.removeChild(element.firstChild);
    }
}
let newChat = undefined;

var loop = setInterval(startInterval, 1500);

function startInterval(){
    const selected = document.getElementsByClassName('ks-active');
    if(selected[0] != undefined){
        selected[0].click();
    }
}


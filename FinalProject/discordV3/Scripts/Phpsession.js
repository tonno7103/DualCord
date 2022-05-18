require("cookies");
const Memcached = require("memcached");

/**
 * 
 * @param {String} name 
 * @param {Request} request 
 * get the memcached data using a key
 * 
 * @returns {JSON | String} 
 */
function getData(name, request){
    return new Promise((resolve, reject) => {
        const Cookies = require( 'cookies' );
        const Memcached = require('memcached');
        const cookies = new Cookies(request)
        const key = cookies.get(name);
        if(key === undefined){
             reject("Nothing to get");
        }

        const connection = new Memcached('localhost:11211');
        console.log("[PHPSESSION.JS] Fetching for key: ", key);
        connection.get(key, (err, data) =>{
            if(err) reject(console.error("Error: ", err));
            if(data === false || data === undefined) reject(console.log("No data found"));
            resolve(data);
        })
    })

}
async function writeData(key, value){
    return new Promise((resolve, reject) => {
        const connection = new Memcached('localhost:11211');
        connection.set(key, value, 100000, (err, data) =>{
            if(err) reject(console.error("Error: ", err));
            resolve(data);
        })
    })
}

async function deleteData(key){
    return new Promise((resolve, reject) => {
        const connection = new Memcached('localhost:11211');
        connection.del(key, (err, data) =>{
            if(err) reject(console.error("Error: ", err));
            resolve(data);
        })
    })
}
module.exports = {getData, writeData, deleteData}
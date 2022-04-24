// set value inside of memcached
const memcached = require('memcached');
const {log} = require("debug");
const client = new memcached('localhost:11211', { retries: 10, retry: 10000, remove: true });

async function setValue(key, value) {
    return new Promise((resolve, reject) => {
        client.set(key, value, 0, (err, result) => {
            if (err) {
                reject(err);
            } else {
                resolve(result);
            }
        });
    });
}

async function getValue(key) {
        return new Promise((resolve, reject) => {
                client.get(key, function (err, result) {
                        if (err) reject(err);
                        resolve(result);
                });
        });
}

setValue('sas', 'value').then(() => {
        getValue('sas').then(r => console.log(r));
});
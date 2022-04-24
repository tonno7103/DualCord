const mysql = require('mysql')
const database = require('../utils/database.json');
const connection = mysql.createConnection({
    host: database['name'],
    user: database['user']['name'],
    password: database['user']['password'],
    database: database['database'],
});
connection.connect(err=>{
    if(err) throw err;
    console.log("QUERY: Connected! ")
});

/**
 * @param {int} userId PK client
 * @param {string} column Name of the column 
 * 
 * @returns {Promise} Value
 */
function getValue(userId, column){
    return new Promise((resolve, reject)=>{
        const query = `SELECT ${column} FROM users WHERE id = ${userId}`
        connection.query(query, (err, result, fileds) =>{
            if (err) reject("error");
            resolve(result);
        });
    });

}

module.exports = {getValue}
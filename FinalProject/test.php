<?php

    $conn = new mysqli(
        hostname: 'localhost',
        username: 'tonno',
        password: 'Password1!',
        database: 'discord'
    );

    $query = 'SELECT * FROM users WHERE id NOT IN (SELECT owner_id FROM messages)';
    $result = $conn->query($query);
    if($result){
        while($user = $result->fetch_assoc()){
            echo $user['username'] . "\n";
        }
    }
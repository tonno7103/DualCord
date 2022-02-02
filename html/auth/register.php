<html lang="eng">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./oauth.css">
    <title>Auth</title>
</head>
<body>
    <div class="main">
        <div class="container" style="text-align: center;">
                <div id="login">
                    <form action="" method="post">
                            <p><span class="fa fa-user"></span>
                                    <input type="text" name="mail" Placeholder="E-mail" required>
                                </p>
                                <p><span class="fa fa-user"></span>
                                    <input type="text" name="username" Placeholder="username" required>
                                </p>
                            <p><span class="fa fa-lock"></span>
                                <input type="password" name="password" Placeholder="Password" required>
                                </p>
                            <p><span class="fa fa-lock"></span>
                                <input type="password" name="confirm" Placeholder="Confirm password" required>
                                </p>
                            <div>
                                <span style="width:100%; text-align:right;  display: grid;"><input type="submit" value="Sign Up"></span>
                            </div>
                    </form>
                </div>
        </div>
    </div>
</body>

</html>
<?php
    if($_POST == null) die;

    $content = file_get_contents("./database.json");
    $data = json_decode($content);
    $mail = $_POST["mail"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
    $connection = new mysqli(
        $data->name,
        $data->user->name,
        $data->user->password,
        $data->database,
        $data->port
    );

    $create = "INSERT INTO `user`(`mail`, `password`, `username`) VALUES ('$mail', '$password', '$username')";
    try{
        $connection->query($create);
    } catch (mysqli_sql_exception $e){
        echo "errore";
        die;
    }
    header("Location: http://localhost:8080");
    
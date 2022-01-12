<html>
<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://localhost:8080/stylesheets/index.css">
    <title>Auth</title>
</head>
<body>
    <div class="main">
        <div class="container" style="text-align: center;">
            <div class="middle">
                <div id="login">
                    <form action="" method="post">
                        <fieldset class="clearfix">
                            <p><span class="fa fa-user"></span><label>
                                    <input type="text" name="username" Placeholder="Username" required>
                                </label></p>
                            <p><span class="fa fa-lock"></span><label>
                                    <input type="password" name="password" Placeholder="Password" required>
                                </label></p>
                            <div>
                                    <span style="width:48%; text-align:left;  display: inline-block;"><a class="small-text" href="#">Forgot
                                    password?</a></span>
                                <span style="width:50%; text-align:right;  display: inline-block;"><input type="submit" value="Sign In"></span>
                            </div>

                        </fieldset>
                        <div class="clearfix"></div>
                    </form>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    if($_POST == null) die;
    // $sql = mysqli_connect(
    //     hostname: "localhost", 
    //     username: "root",
    //     password: "Password1!",
    // ) or die("Connection to db failed");

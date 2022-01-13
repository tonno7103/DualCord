<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="http://localhost/home/stylesheets/index.css">
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
                                    <input type="text" name="mail" Placeholder="Mail" required>
                                </label></p>
                            <p><span class="fa fa-lock"></span><label>
                                <input type="password" name="password" Placeholder="Password" required>
                                </label></p>
                            <p><span class="fa fa-lock"></span><label>
                                <input type="password" name="confirm" Placeholder="Confirm password" required>
                                </label></p>
                            <div>
                                <span style="width:100%; text-align:right;  display: grid;"><input type="submit" value="Sign Up"></span>
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
    die;
<?php

// Start Session
session_start();

// check user login
if(empty($_SESSION['user_id']))
{
    header("Location: index.php");
}

// Database connection
require __DIR__ . '/config/db_connection.php';
$db = DB();

// Application library ( with DemoLib class )
require __DIR__ . '/library/library.php';
$app = new DemoLib($db);
$user = $app->UserDetails($_SESSION['user_id']);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row jumbotron">
        <div class="col-md-12">
            <h2>
                Demo: Using Google Two factor authentication in PHP
            </h2>
            <p>
                Note: This is demo version from iTech Empires tutorials. (Multi-factor Authentication)
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 col-md-offset-3 well">
            <h2>User Profile</h2>
            <h4>Welcome <?php echo $user->FirstName; ?></h4>
            <p>Account Details:</p>
            <p>Name: <?php echo $user->LastName; ?></p>
            <p>Username: <?php echo $user->LastName; ?></p>
            <p>Email: <?php echo $user->Email; ?></p>
            <br>
            Click here to <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

</body>
</html>
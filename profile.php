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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Zip Rentals</title><link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="js/main.js" type="module"></script>
</head>
<body>
  <h4>Signed In As: <?php echo $user->FirstName; ?></h4>
  <a href="logout.php" id="btnLogout" class="btn btn-info">Logout</a>
  <div class="register2">
      <header class="header"><span>Zip Rentals</span></header>
      <div><span>&quot;Rent the Perfect House&quot;</span></div>
      <div>
          <div><span>Enter A Zipcode:</span></div><input id="Zipcode" type="number" placeholder="Enter Zipcode Here" />
      </div><button id="btnZip" class="btn btn-info">Submit</button>
  </div>
  <div id="Houses"></div>
</body>
</html>

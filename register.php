<?php

// Start Session
session_start();

// Database connection
require __DIR__ . '/config/db_connection.php';
$db = DB();

// Application library ( with DemoLib class )
require __DIR__ . '/library/library.php';
$app = new DemoLib($db);

require_once __DIR__ . '/GoogleAuthenticator/GoogleAuthenticator.php';
$pga = new PHPGangsta_GoogleAuthenticator();
$secret = $pga->createSecret();

$register_error_message = '';

// check Register request
if (!empty($_POST['btnRegister'])) {
    if ($_POST['name'] == "") {
        $register_error_message = 'Name field is required!';
    } else if ($_POST['email'] == "") {
        $register_error_message = 'Email field is required!';
    } else if ($_POST['username'] == "") {
        $register_error_message = 'Username field is required!';
    } else if ($_POST['password'] == "") {
        $register_error_message = 'Password field is required!';
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $register_error_message = 'Invalid email address!';
    } else if ($app->isEmail($_POST['email'])) {
        $register_error_message = 'Email is already in use!';
    } else if ($app->isUsername($_POST['username'])) {
        $register_error_message = 'Username is already in use!';
    } else {
        $user_id = $app->Register($_POST['name'], $_POST['email'], $_POST['username'], $_POST['password'], $secret);
        // set session and redirect user to the profile page
        $_SESSION['user_id'] = $user_id;
        header("Location: confirm_google_auth.php");
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
  <div class="register">
      <header class="header">
          <div>
              <span>Create An Account</span>
          </div>
      </header>
      <?php
      if ($register_error_message != "") {
          echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $register_error_message . '</div>';
      }
      ?>
      <form action="register.php" method="post">
      <div>
          <label class="form-label form-label form-label form-label form-label">First Name:</label><br>
          <input type="text" id="Firstname" name="name" placeholder="Enter First Name">
      </div>
      <div>
          <label class="form-label form-label form-label form-label form-label">Email:</label><br>
          <input type="email" id="Email" name="email" placeholder="Enter Email">
      </div>
      <div>
          <label class="form-label form-label form-label form-label form-label">Username:</label><br>
          <input type="text" id="Username" name="username" placeholder="Enter Username">
      </div>
      <div>
          <label class="form-label form-label form-label form-label form-label">Password:</label><br>
          <input type="password" id="Password" name="password" placeholder="Enter Password">
      </div>
      <div>
          <input type="text" class="form-control-plaintext">
      </div>
      <div class="form-group">
        <input type="submit" name="btnRegister" class="btn btn-primary" value="Register" id="btnRegister" />
      </div>
    </form>
      <div class="form-group">
          <span>If You Already Made An Account: </span>
          <a href="index.php">Login</a>
      </div>
  </div>
</body>
</html>

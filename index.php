<?php

// Start Session
session_start();

// Database connection
require __DIR__ . '/config/db_connection.php';
$db = DB();
//print "After DB open <br>";

// Application library ( with DemoLib class )
require __DIR__ . '/library/library.php';
$app = new DemoLib($db);
//print "After library open <br>";

$login_error_message = '';

// check Login request
if (!empty($_POST['btnLogin'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "") {
        $login_error_message = 'Username field is required!';
    } else if ($password == "") {
        $login_error_message = 'Password field is required!';
    } else {
        $user_id = $app->Login($username, $password); // check user login
        if($user_id > 0)
        {
            $_SESSION['user_id'] = $user_id; // Set Session
            header("Location: validate_login.php"); // Redirect user to validate auth code
        }
        else
        {
            $login_error_message = 'Invalid login details!';
        }
    }
}

require_once __DIR__ . '/GoogleAuthenticator/GoogleAuthenticator.php';
$pga = new PHPGangsta_GoogleAuthenticator();
$secret = $pga->createSecret();

//print "Received secret <br>";


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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="css/styles.min.css">
</head>
<body>
  <div  class="container align-items-xl-start text-start d-xl-flex justify-content-xl-center align-items-xl-center" style="margin-top: 10%;margin-right: 15%;margin-bottom: -50px;margin-left: 15%;">
      <div id="LoginDiv" class="row" style="background: #ffffff;border-top-left-radius: 25px;border-top-right-radius: 25px;border-bottom-right-radius: 25px;border-bottom-left-radius: 25px;">
          <div class="col-md-6 col-xl-5" style="background: url(&quot;images/BcaKbjBni.jpg&quot;) center / contain no-repeat;width: 450px;height: 405px;">
              <div></div>
          </div>
          <div class="col-md-6 col-xl-6" style="margin-bottom: 18px;padding-bottom: 0px;height: 300px;margin-top: 42px;">
              <form action="index.php" method="post" style="width: 450px;">
                  <div>
                      <p style="font-size: 30px;text-align: left;">User Login<br></p>
                  </div>
                  <?php
                  if ($login_error_message != "") {
                      echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $login_error_message . '</div>';
                  }
                  ?>
                  <div class="form-group" style="background: var(--bs-gray-300);border-radius: 25px;border-style: none;width: 450px;">
                      <i class="fas fa-user" style="margin-left: 10px;"></i>
                      <input id="txtUsername" name="username" type="text" style="color: var(--bs-black);background: rgba(206,212,218,0);margin-left: 12px;padding: 4px;border-color: rgba(0,0,0,0);border-bottom-color: rgba(0,0,0,0);" placeholder="Username/Email" required="">
                  </div>
                  <div class="form-group" style="background: var(--bs-gray-300);border-radius: 25px;border-style: none;margin-top: 15px;width: 450px;">
                      <i class="fas fa-lock" style="margin-left: 10px;"></i>
                      <input id="txtPassword" name="password" type="text" style="color: var(--bs-black);background: rgba(206,212,218,0);margin-left: 12px;padding: 4px;border-color: rgba(0,0,0,0);border-bottom-color: rgba(0,0,0,0);" placeholder="Password" required="">
                  </div>
                  <div class="form-group"  style="background: var(--bs-gray-300);border-radius: 25px;border-style: none;margin-top: 15px;">
                      <input id="btnLogin" name="btnLogin" class="btn btn-primary" type="submit" value="Login" style="width: 448.6px;"></input>
                  </div>
                  <div>
                      <p style="font-size: 15px;margin-top: 10px;color: var(--bs-secondary);text-align: center;width: 450px;">Need an Account?&nbsp;
                          <a href="register.php">Register</a>
                      </p>
                      <p style="font-size: 15px;margin-top: -14px;color: var(--bs-secondary);text-align: center;width: 450px;">Forgot Password?&nbsp;
                          <a href="#">Reset Password</a>
                      </p>
                  </div>
              </form>
          </div>
      </div>
  </div>
</body>
</html>

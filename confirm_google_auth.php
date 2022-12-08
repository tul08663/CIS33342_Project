<?php

// Start Session
session_start();

// Database connection
require __DIR__ . '/config/db_connection.php';
$db = DB();
print "DB Opened2 <br>";

// Application library ( with DemoLib class )
require __DIR__ . '/library/library.php';
$app = new DemoLib($db);
print "Got DB. user_id[".$_SESSION['user_id']."]<br>";
$user = $app->UserDetails($_SESSION['user_id']);
//print "Get User detail:[".$user."] <br>";

require_once __DIR__ . '/GoogleAuthenticator/GoogleAuthenticator.php';
$pga = new PHPGangsta_GoogleAuthenticator();
$qr_code =  $pga->getQRCodeGoogleUrl($user->Email, $user->googleSecret, 'cis3342.temple.edu');

$error_message = '';
print "Got QR code <br>";


if (isset($_POST['btnValidate'])) {

    $code = $_POST['code'];

    if ($code == "") {
        $error_message = 'Please Scan above QR code to configure your application and enter genereated authentication code to validated!';
    }
    else
    {
        if($pga->verifyCode($user->googleSecret, $code, 2))
        {
            // success
            header("Location: profile.php");
        }
        else
        {
            // fail
            $error_message = 'Invalid Authentication Code!';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm User Device</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
  <div class="register">
      <header class="header1">
          <div>
              <span>Application Authentication</span>
          </div>
      </header>
      <p>
          Please download and install Google authenticate app on your phone, and scan following QR code to configure your device.
      </p>

      <div class="form-group">
          <img src="<?php echo $qr_code; ?>">
      </div>

      <form method="post" action="confirm_google_auth.php">
          <?php
          if ($error_message != "") {
              echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $error_message . '</div>';
          }
          ?>
      <div>
          <label class="form-label form-label form-label form-label">Enter Authencation Code:</label><br />
          <input id="authCode" type="text" name="code" placeholder="6 Digit Code" />
      </div>
      <div>
          <button id="btnSubmit" type="submit" name="btnValidate" class="btn btn-primary">Validate</button>
      </div>
    </form>
      <div class="form-group">Go Back to <a href="index.php"> Login </a></div>
  </div>
</body>
</html>

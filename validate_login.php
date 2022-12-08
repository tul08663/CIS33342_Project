<?php
// Start Session
session_start();

// Database connection
require __DIR__ . '/config/db_connection.php';
$db = DB();

// Application library ( with DemoLib class )
require __DIR__ . '/library/library.php';
$app = new DemoLib($db);
$user = $app->UserDetails($_SESSION['user_id']);

require_once __DIR__ . '/GoogleAuthenticator/GoogleAuthenticator.php';
$pga = new PHPGangsta_GoogleAuthenticator();

$error_message = '';

if (isset($_POST['btnValidate'])) {

    $code = $_POST['code'];

    if ($code == "") {
        $error_message = 'Please enter authentication code to validated!';
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
    <title>Validate Login</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
  <div class="register">
      <header class="header1">
          <div>
              <span>Multi Factor Authentication</h4>
          </div>
      </header>
      <form method="post" action="validate_login.php">
        <?php
        if ($error_message != "") {
            echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $error_message . '</div>';
        }
        ?>
          <label class="form-label form-label form-label form-label">Authencation Code:</label><br />
          <input id="authCode" type="text" name="code" placeholder="Enter Authencation Code" />
          <div class="form-group">
            <button type="submit" id="btnSubmit" name="btnValidate" class="btn btn-primary">Validate</button>
          </div>
      </form>
      <div class="form-group">
          Click here to <a href="index.php">Login</a> if you have already registered your account.
      </div>
  </div>
</body>
</html>

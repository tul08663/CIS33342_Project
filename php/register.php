<?php
  session_start();
  require_once("config.php");

  // Database connection
  require __DIR__ . '/../MFA/config/db_connection.php';
  $db = DB();

  // Application library ( with DemoLib class )
  require __DIR__ . '/../MFA/library/library.php';
  $app = new DemoLib($db);

  require_once __DIR__ . '/../MFA/GoogleAuthenticator/GoogleAuthenticator.php';
  $pga = new PHPGangsta_GoogleAuthenticator();
  $secret = $pga->createSecret();

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  use PHPMailer\PHPMailer\SMTP;
  require "../../PHPMailer-master/src/Exception.php";
  require "../../PHPMailer-master/src/PHPMailer.php";
  require "../../PHPMailer-master/src/SMTP.php";

  //Connect to database
  $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);
  if (!$con) {
    $_SESSION["Message"] = "DB Connection error: ".$e->errorMessage();
    $_SESSION["RegState"] = -1;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    //print "DB Connection failed: ".mysqli_error($con);
    exit();
  }

  //Get web data
  $firstName = mysqli_real_escape_string($con, $_GET["first-name"]);
  $lastName = mysqli_real_escape_string($con, $_GET["last-name"]);
  $email = mysqli_real_escape_string($con, $_GET["email"]);

  //Insert into database
  $Rdatetime = date("Y-m-d h:i:s");
  $Acode = rand(100000, 999999);
  $query = "INSERT INTO USERS(FirstName, LastName, Email, Rdatetime, Acode, Status, googleSecret)
            VALUES ('$firstName', '$lastName', '$email', '$Rdatetime', '$Acode', 0, '$secret');";
  $result = mysqli_query($con, $query);
  if (!$result) {
    $_SESSION["Message"] = "Query error: ".$e->errorMessage();
    $_SESSION["RegState"] = -2;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $_SESSION["user_id"] = mysqli_insert_id($con);

  //Build the PHPMailer object:
  $mail= new PHPMailer(true);
  try {
    $mail->SMTPDebug = 0;
    $mail->IsSMTP();
    $mail->Host="smtp.gmail.com";
    $mail->SMTPAuth=true;
    $mail->Username="cis3342.93221@gmail.com";
    $mail->Password = "jjltixfyooryyatf";
    $mail->SMTPSecure = "ssl";
    $mail->Port=465;
    $mail->SMTPKeepAlive = true;
    $mail->Mailer = "smtp";
    $mail->setFrom("tuj93221@temple.edu", "Olamide Akinsola");
    $mail->addReplyTo("tuj93221@temple.edu","Olamide Akinsola");
    $msg = "Welcome to Zip Rentals! Here is your Authentication Code: $Acode. Please complete your registration on the website.";
    $mail->addAddress($email,"$firstName $lastName");
    $mail->Subject = "Zip Rentals - Register";
    $mail->Body = $msg;
    $mail->send();
    $_SESSION["RegState"] = 5; //Google auth
    $_SESSION["Message"] = "Email sent to ($email)";
    $_SESSION["Email"] = $email;
  } catch (phpmailerException $e) {
    $_SESSION["Message"] = "Mailer error: ".$e->errorMessage();
    //print "Mail failed to send: ".$e->errorMessage;
  }

  // header("Location:../MFA/confirm_google_auth.php");
  $data["Message"] = $_SESSION["Message"];
  $data["RegState"] = $_SESSION["RegState"];
  $data["Email"] = $_SESSION["Email"];
  echo json_encode($data);
  exit();
?>

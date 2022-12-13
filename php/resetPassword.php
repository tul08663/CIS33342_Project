<?php
  session_start();
  require_once("config.php");
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  use PHPMailer\PHPMailer\SMTP;
  require "../../PHPMailer-master/src/Exception.php";
  require "../../PHPMailer-master/src/PHPMailer.php";
  require "../../PHPMailer-master/src/SMTP.php";

  //Connect to database
  $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);
  if (!$con) {
    $_SESSION["Message"] = "DB Connection error.";
    $_SESSION["RegState"] = 3;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  //Get web data
  $email = $_POST["reset-password-email"];

  //Retrieve user with matching email from database
  $query = "SELECT * FROM USERS WHERE Email='$email';";
  $result = mysqli_query($con, $query);

  if (!$result) {
    $_SESSION["Message"] = "Query error.";
    $_SESSION["RegState"] = 3;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  if (mysqli_num_rows($result) != 1) {
    $_SESSION["Message"] = "The account with this email doesn't exist.";
    $_SESSION["RegState"] = 3;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  //Retrieve Acode from database
  $row = mysqli_fetch_assoc($result);
  $Acode = $row["Acode"];

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
    $msg = "You have requested a password reset. Here is your Authentication Code: $Acode. Please reset your password on the website.";
    $mail->addAddress($email,"$firstName $lastName");
    $mail->Subject = "Zip Rentals - Reset Password";
    $mail->Body = $msg;
    $mail->send();
    $_SESSION["RegState"] = 3;
    $_SESSION["Message"] = "Email sent to ($email)";
    $_SESSION["Email"] = $email;
  } catch (phpmailerException $e) {
    $_SESSION["Message"] = "Mailer error.";
    $_SESSION["RegState"] = -3;
    //print "Mail failed to send: ".$e->errorMessage;
  }

  $data["Message"] = $_SESSION["Message"];
  $data["RegState"] = $_SESSION["RegState"];
  $data["Email"] = $_SESSION["Email"];
  echo json_encode($data);
  exit();
?>

<?php
  session_start();
  require_once("config.php");

  //Connect to database
  $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);
  if (!$con) {
    $_SESSION["Message"] = "DB Connection error: ".$e->errorMessage();
    $_SESSION["RegState"] = 0;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  //Get webdata
  $email = mysqli_real_escape_string($con, $_POST["login-email"]);
  $password = mysqli_real_escape_string($con, md5($_POST["login-password"]));

  //Retrieve user from database
  $query = "SELECT * FROM USERS WHERE Email='$email' AND Password='$password';";
  $result = mysqli_query($con, $query);

  if (!$result) {
    $_SESSION["Message"] = "Query error: ".$e->errorMessage();
    $_SESSION["RegState"] = 0;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  if (mysqli_num_rows($result) != 1) {
    $_SESSION["Message"] = "Login failed. Email or password doesn't match.";
    $_SESSION["RegState"] = 0;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $row = mysqli_fetch_assoc($result);
  $_SESSION["FirstName"] = $row["FirstName"];
  $_SESSION["LastName"] = $row["LastName"];
  $_SESSION["Email"] = $email;
  $_SESSION["RegState"] = 4;
  $_SESSION["Message"] = "Login success";

  $Ldatetime = date("Y-m-d h:i:s");

  //Record login time in database
  $query = "UPDATE USERS SET Ldatetime = '$Ldatetime' where Email = '$email';";
  $result = mysqli_query($con, $query);

  if (!$result) {
    $_SESSION["Message"] = "Query error: ".$e->errorMessage();
    $_SESSION["RegState"] = 0;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  if (mysqli_affected_rows($con) != 1) {
    $_SESSION["Message"] = "Login cookie update failed.".mysqli_error($con);
    $_SESSION["RegState"] = 0;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $data["FirstName"] = $_SESSION["FirstName"];
  $data["Email"] = $_SESSION["Email"];
  $data["Message"] = $_SESSION["Message"];
  $data["RegState"] = $_SESSION["RegState"];
  echo json_encode($data);
  exit();
?>

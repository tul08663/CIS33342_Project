<?php
  session_start();
  require_once("config.php");

  //Connect to database
  $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);
  if (!$con) {
    $_SESSION["Message"] = "DB Connection error: ".$e->errorMessage();
    $_SESSION["RegState"] = 2;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  //Get webdata
  $password1 = md5($_POST["new-password"]);
  $password2 = md5($_POST["reenter-password"]);

  //Compare passwords
  if ($password1 != $password2) {
    $_SESSION["RegState"] = 2;
    $_SESSION["Message"] = "Passwords don't match. Try again.";
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  // Get RCount
  $email = $_SESSION["Email"];
  $query = "SELECT * FROM USERS WHERE Email='$email';";
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
    $_SESSION["Message"] = "Query error.";
    $_SESSION["RegState"] = 0;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $row = mysqli_fetch_assoc($result);
  $Rcount = $row["RCount"] + 1;

  //Update user's password, rcount, and acode in database
  $Adatetime = date("Y-m-d h:i:s");
  $Acode = rand(100000, 999999);
  $query = "UPDATE USERS SET Password='$password1', Acode='$Acode', Adatetime='$Adatetime', RCount='$Rcount' WHERE Email='$email';";
  $result = mysqli_query($con, $query);
  if (!$result) {
    $_SESSION["Message"] = "Query error: ".$e->errorMessage();
    $_SESSION["RegState"] = 2;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  if (mysqli_affected_rows($con) == 0) {
    $_SESSION["Message"] = "Query error: ".$e->errorMessage();
    $_SESSION["RegState"] = 2;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $_SESSION["Message"] = "Password set. Please log in.";
  $_SESSION["RegState"] = 0;
  $data["Message"] = $_SESSION["Message"];
  $data["RegState"] = $_SESSION["RegState"];
  echo json_encode($data);
  exit();
 ?>

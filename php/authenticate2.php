<?php
  session_start();
  require_once("config.php");
  $Acode = $_POST["acode2"];
  $email = $_SESSION["Email"];
  $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);
  if (!$con) {
    $_SESSION["Message"] = "DB Connection error: ".$e->errorMessage();
    $_SESSION["RegState"] = 3;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $query = "SELECT * FROM USERS WHERE Email='$email' AND Acode='$Acode';";
  $result = mysqli_query($con, $query);
  if (!$result) {
    $_SESSION["Message"] = "Query error: ".$e->errorMessage();
    $_SESSION["RegState"] = 3;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  if (mysqli_num_rows($result) != 1) {
    $_SESSION["Message"] = "Acode or email is incorrect.";
    $_SESSION["RegState"] = 3;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $_SESSION["RegState"] = 2;
  $_SESSION["Message"] = "Authentication success. Please set password.";
  $data["Message"] = $_SESSION["Message"];
  $data["RegState"] = $_SESSION["RegState"];
  echo json_encode($data);
  exit();

?>
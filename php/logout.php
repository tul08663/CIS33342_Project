<?php
  session_start();
  require_once("config.php");

  $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);
  if (!$con) {
    $_SESSION["Message"] = "DB Connection error: ".$e->errorMessage();
    $_SESSION["RegState"] = 4;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $LOdatetime = date("Y-m-d h:i:s");
  $email = $_SESSION["Email"];

  $query = "UPDATE USERS SET LOdatetime='$LOdatetime' WHERE Email='$email';";
  $result = mysqli_query($con, $query);

  if (!$result) {
    $_SESSION["Message"] = "Query error: ".$e->errorMessage();
    $_SESSION["RegState"] = 4;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  if (mysqli_affected_rows($con) != 1) {
    $_SESSION["Message"] = "Logout failed.".mysqli_error($con);
    $_SESSION["RegState"] = 4;
    $data["Message"] = $_SESSION["Message"];
    $data["RegState"] = $_SESSION["RegState"];
    echo json_encode($data);
    exit();
  }

  $_SESSION["RegState"] = 0;
  $data["RegState"] = $_SESSION["RegState"];
  echo json_encode($data);
  exit();
 ?>

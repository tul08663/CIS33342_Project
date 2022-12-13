<?php
  session_id(md5("Olamide-A"));
  session_start();
  if (!isset($_SESSION["RegState"])) $_SESSION["RegState"] = 0;
  if ($_SESSION["RegState"] != 6) {
      $_SESSION["Message"] = "Please login or register.";
      echo json_encode($_SESSION["RegState"]);
      exit();
  }
  echo json_encode($_SESSION["RegState"]);
  //json_encode($_SESSION);
  exit();
 ?>

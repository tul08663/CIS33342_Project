<?php
  session_start();
  echo json_encode($_SESSION["RegState"] = 0);
  exit();
?>

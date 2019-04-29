<?php
  session_start();

  require_once "../php-classes/user.php";

  $user = new User();
  $sign_in_message = $user->sign_in();
  $_SESSION['sign_in_message'] = $sign_in_message;

  header('Location: ../user-interactions/index.php');
  
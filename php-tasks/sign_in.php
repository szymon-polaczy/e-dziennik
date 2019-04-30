<?php
  session_start();

  require_once "../php-classes/user.php";

  $class_user = new USER();
  $sign_in_message = $class_user->sign_in();

  header('Location: ../user-interactions/journal.php');
  
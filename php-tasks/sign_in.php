<?php
  session_start();

  require_once "../php-classes/user.php";
  require_once "../php-classes/pdo.php";
  require_once "files-needed/connect.php";

  if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header('Location: ../user-interactions/index.php');
    exit();
  }

  $class_user = new USER();
  $class_pdo_db = new PDO_DB($db_user, $db_password, $db_name, $host);

  $email = $_POST['email'];
  $password = $_POST['password'];

  $_SESSION['sign_in_message'] = $class_user->sign_in($class_pdo_db, $email, $password);

  if ($_SESSION['sign_in_message'] === 0) {
    unset($_SESSION['sign_in_message']);
    header('Location: ../user-interactions/journal.php');
  }
  else {
    header('Location: ../user-interactions/index.php');
  }
  
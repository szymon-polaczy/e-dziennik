<?php
  session_start();

  require_once "../php-classes/users.php";
  require_once "../php-classes/pdo.php";
  require_once "files-needed/connect.php";

  if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header('Location: ../user-interactions/index.php');
    exit();
  }

  $class_users = new USERS();
  $class_pdo_db = new PDO_DB($db_user, $db_password, $db_name, $host);

  $email = $_POST['email'];
  $password = $_POST['password'];

  $_SESSION['sign_in_message'] = $class_users->sign_in($class_pdo_db, $email, $password);

  if ($_SESSION['sign_in_message'] === "Good.") {
    unset($_SESSION['sign_in_message']);
    header('Location: ../user-interactions/journal.php');
  }
  else {
    header('Location: ../user-interactions/index.php');
  }
  
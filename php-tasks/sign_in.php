<?php
  session_start();

  require_once "files-needed/connect.php";
  require_once "../php-classes/UserManager.php";
  require_once "../php-classes/PdoManager.php";

  if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header('Location: ../user-interactions/index.php');
    exit();
  }

  $pdo_manager = new PdoManager($db_user, $db_password, $db_name, $host);
  $user_manager = new UserManager();
  
  $email = $_POST['email'];
  $password = $_POST['password'];

  $_SESSION['sign_in_message'] = $user_manager->signIn($pdo_manager, $email, $password);

  if ($_SESSION['sign_in_message'] === "Good.") {
    unset($_SESSION['sign_in_message']);
    header('Location: ../user-interactions/journal.php');
  }
  else {
    header('Location: ../user-interactions/index.php');
  }
  
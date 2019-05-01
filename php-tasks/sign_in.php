<?php
  session_start();

  require_once "../php-classes/user.php";

  require_once "../php-classes/pdo.php";

  $class_user = new USER();
  
  $class_pdo_db = new PDO_DB('root', '<kizdeR<', 'school-journal');

  $email = $_POST['email'];
  $password = $_POST['password'];

  $_SESSION['sign_in_message'] = $class_user->sign_in($class_pdo_db, $email, $password);

  if ($_SESSION['sign_in_message'] == 0)
    header('Location: ../user-interactions/journal.php');
  else
    header('Location: ../user-interactions/index.php');
  
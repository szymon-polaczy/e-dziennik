<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (isset($_POST['semail']) && isset($_POST['nemail'])) {
    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $wszystko_ok = true;
    $semail = $_POST['semail'];
    $nemail = $_POST['nemail'];

    //Sprawdzanie czy stary email zgadza się z prawdą
    $moje_id = $_SESSION['id'];
    $sql = "SELECT email FROM osoba WHERE id='$moje_id'";

    $rezultat = $pdo->sql_field($sql);

    if ($semail != $rezultat[0]) {
      $wszystko_ok = false;
      $_SESSION['zmiana_emailu'] = "Podaj poprawny stary adres email!";
    }

    //Sprawdzanie poprawności adresu email
    $nemailB = filter_var($nemail, FILTER_SANITIZE_EMAIL);
    if((filter_var($nemailB, FILTER_VALIDATE_EMAIL) == false) || ($nemail != $nemailB)) {
      $wszystko_ok = false;
      $_SESSION['zmiana_emailu'] = "Podaj poprawny nowy adres email!";
    }

    //Sprawdzanie czy nowy email nie jest już w bazie danych
    $sql = "SELECT email FROM osoba WHERE email='$nemailB'";

    $rezultat = $pdo->sql_field($sql);

    if (count($rezultat) > 0) {
      $wszystko_ok = false;
      $_SESSION['zmiana_emailu'] = "Taki adres email istnieje już w bazie!";
    }

    //Jeśli wszystlo poszło ok to zmieniam email
    if ($wszystko_ok) {
      $moje_id = $_SESSION['id'];
      $sql = "UPDATE osoba SET email='$nemail' WHERE id='$moje_id'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['zmiana_emailu'] = "Email został zmieniony!";
      else
        $_SESSION['zmiana_emailu'] = "Email nie został zmieniony!";
    }
  }

  header('Location: ../zmien_dane.php');

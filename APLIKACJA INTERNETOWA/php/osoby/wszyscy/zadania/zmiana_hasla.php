<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  if (isset($_POST['shaslo']) && isset($_POST['nhaslo'])) {
    $wszystko_ok = true;

    $shaslo = $_POST['shaslo'];
    $nhaslo = $_POST['nhaslo'];

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //Sprawdzanie czy stare hasło zgadza się z prawdą
    $moje_id = $_SESSION['id'];
    $sql = "SELECT haslo FROM osoba WHERE id='$moje_id'";

    $rezultat = $pdo->sql_field($sql);

    if (!password_verify($shaslo, $rezultat[0])) {
      $wszystko_ok = false;
      $_SESSION['zmiana_hasla'] = "Podaj poprawne stare hasło!";
    }

    //Sprawdzanie długości hasła
    if (strlen($nhaslo) < 8 || strlen($nhaslo) > 32) {
      $wszystko_ok = false;
      $_SESSION['zmiana_hasla'] = "Hasło musi posiadać więcej niż 8 znaków oraz mniej niż 32!";
    }

    //Jeśli wszystlo poszło ok to zmieniam hasło
    if ($wszystko_ok) {
      $haslo_hash = password_hash($nhaslo, PASSWORD_DEFAULT);

      $moje_id = $_SESSION['id'];
      $sql = "UPDATE osoba SET haslo='$haslo_hash' WHERE id='$moje_id'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['zmiana_hasla'] = "Hasło zostało zmienione!";
      else
        $_SESSION['zmiana_hasla'] = "Hasło nie zostało zmienione!";
    }
  }

  header('Location: ../zmien_dane.php');

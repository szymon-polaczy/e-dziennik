<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_POST['nazwa']) || !isset($_POST['wyb_przedmiot'])) {
    header('Location: admin_przydzialy.php');
    exit();
  }

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  if (isset($_POST['nazwa']) && isset($_POST['wyb_przedmiot'])) {
    $wyb_przedmiot = $_POST['wyb_przedmiot'];
    $nazwa = $_POST['nazwa'];
    $wszystko_ok = true;

    //sprawdzenie długości nazwy
    if(strlen($nazwa) < 2 || strlen($nazwa) > 50) {
      $wszystko_ok = false;
      $_SESSION['edytowanie_przedmiotow'] = "Nazwa musi mieć pomiędzy 2 a 50 znaków!";
    }

    //sprawdzanie czy przedmiot o takiej nazwie już istnieje
    $sql = "SELECT id FROM przedmiot WHERE nazwa='$nazwa'";

    $rezultat = $pdo->sql_table($sql);

    if (count($rezultat) > 0) {
      $_SESSION['edytowanie_przedmiotow'] = "Przedmiot o takiej nazwie już istnieje!";
        $wszystko_ok = false;
    }

    //jeśli wszystko poszło ok to robię update
    if ($wszystko_ok) {
      $sql = "UPDATE przedmiot SET nazwa='$nazwa' WHERE id='$wyb_przedmiot'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['edytowanie_przedmiotow'] = "Przedmiot został edytowany.";
      else
        $_SESSION['edytowanie_przedmiotow'] = "Przedmiot się nie zmienił.";
    }
  }

  header('Location: ../admin_przedmioty.php');

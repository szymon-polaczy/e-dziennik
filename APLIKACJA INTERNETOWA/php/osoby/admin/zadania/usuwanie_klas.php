<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  if (isset($_GET['wyb_klasa'])) {
    $wszystko_ok = true;
    $wyb_klasa = $_GET['wyb_klasa'];
    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //Sprawdzanie czy dana klasa jest w jakimś przydziale
    $sql = "SELECT * FROM przydzial WHERE id_klasa='$wyb_klasa'";

    $rezultat = $pdo->sql_field($sql);

    if (count($rezultat) > 0) {
      $wszystko_ok = false;
      $_SESSION['usuwanie_klas'] = "Nie można usunąć danej klasy, ponieważ jest połączona z przydziałem!";
    }

    //Sprawdzanie czy dana klasa jest w jakimś przydziale
    $sql = "SELECT * FROM uczen WHERE id_klasa='$wyb_klasa'";

    $rezultat = $pdo->sql_field($sql);

    if (count($rezultat) > 0) {
      $wszystko_ok = false;
      $_SESSION['usuwanie_klas'] = "Nie można usunąć danej klasy, ponieważ jest połączona z uczniem!";
    }

    //Jeżeli wszystko poszło ok to usuwam klasę
    if ($wszystko_ok) {
      $sql = "DELETE FROM klasa WHERE id='$wyb_klasa'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['usuwanie_klas'] = "Klasa została usunięta!";
      else
        $_SESSION['usuwanie_klas'] = "Klasa nie została usunięta!";
    }
  }

  header('Location: ../admin_klasy.php');

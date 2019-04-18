<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (isset($_GET['wyb_przydzial'])) {
    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $wyb_przydzial = $_GET['wyb_przydzial'];

    $wszystko_ok = true;

    //Zabezpieczenie jeśli są jakieś oceny do danego przydziału
    $sql = "SELECT * FROM ocena WHERE id_przydzial='$wyb_przydzial'";

    $rezultat = $pdo->sql_table($sql);

    if (count($rezultat) > 0) {
      $_SESSION['usuwanie_przydzialow'] = "Ten przydział jest powiązany z ocenami, nie można go usunąć!";
      $wszystko_ok = false;
    }

    //Jeśli wszystko poszło ok to usuwam przydział
    if ($wszystko_ok) {
      $sql = "DELETE FROM przydzial WHERE id='$wyb_przydzial'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['usuwanie_przydzialow'] = "Przydział został usunięty";
      else
        $_SESSION['usuwanie_przydzialow'] = "Przydział nie został usunięty";
    }
  }

  header('Location: ../admin_przydzialy.php');

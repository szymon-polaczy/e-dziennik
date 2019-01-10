<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (isset($_GET['wyb_przedmiot'])) {
    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $wyb_przedmiot = $_GET['wyb_przedmiot'];
    $wszystko_ok = true;

    //Sprawdzam czy przedmiot nie jest gdzieś w jakimś przydziale
    $sql = "SELECT id FROM przydzial WHERE id_przedmiot='$wyb_przedmiot'";

    $rezultat = $pdo->sql_table($sql);

    if (count($rezultat) > 0) {
      $_SESSION['usuwanie_przedmiotow'] = "Przedmiot jest przypisany do przydziałów, nie można go usunąć!";
      $wszystko_ok = false;
    }

    //Jeśli wszystko poszło ok to usuwam przedmiot
    if ($wszystko_ok) {
      $sql = "DELETE FROM przedmiot WHERE id='$wyb_przedmiot'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['usuwanie_przedmiotow'] = "Przedmiot został usunięty.";
      else
        $_SESSION['usuwanie_przedmiotow'] = "Przedmiot nie został usunięty.";
    }
  }

  header('Location: ../admin_przedmioty.php');

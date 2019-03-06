<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (isset($_POST['wyb_nauczyciel']) && isset($_POST['wyb_przedmiot']) && isset($_POST['wyb_klasa'])) {
    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $wyb_nauczyciel = $_POST['wyb_nauczyciel'];
    $wyb_przedmiot = $_POST['wyb_przedmiot'];
    $wyb_klasa = $_POST['wyb_klasa'];

    $wszystko_ok = true;

    //Sprawdzam czy taki przydział już nie istnieje
    $sql = "SELECT * FROM przydzial WHERE id_nauczyciel='$wyb_nauczyciel' AND id_przedmiot='$wyb_przedmiot' AND id_klasa='$wyb_klasa'";

    $rezultat = $pdo->sql_table($sql);

    if (count($rezultat) > 0) {
      $wszystko_ok = false;
      $_SESSION['edytowanie_przydzialow'] = "Taki przydział już istnieje!";
    }

    //Jeśli wszystko poszło ok to edytuję przydział
    if ($wszystko_ok) {
      $edytowany_id = $_SESSION['edytowany_id'];
      $sql = "UPDATE przydzial SET id_nauczyciel='$wyb_nauczyciel', id_klasa='$wyb_klasa', id_przedmiot='$wyb_przedmiot' WHERE id='$edytowany_id'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['edytowanie_przydzialow'] = "Przydział został zedytowany";
      else
        $_SESSION['edytowanie_przydzialow'] = "Przydział nie został zedytowany";
    }
  }
  header('Location: ../admin_przydzialy.php');

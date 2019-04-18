<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (isset($_POST['wyb_klasa']) && isset($_POST['wyb_przedmiot']) && isset($_POST['wyb_nauczyciel'])) {
    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $wyb_nauczyciel = $_POST['wyb_nauczyciel'];
    $wyb_przedmiot = $_POST['wyb_przedmiot'];
    $wyb_klasa = $_POST['wyb_klasa'];

    $wszystko_ok = true;

    //Sprawdzam czy taki przydział już nie istnieje
    $sql = "SELECT * FROM przydzial WHERE id_nauczyciel='$wyb_nauczyciel'
            AND id_przedmiot='$wyb_przedmiot' AND id_klasa='$wyb_klasa'";

    $rezultat = $pdo->sql_table($sql);

    if (count($rezultat) > 0) {
      $_SESSION['dodawanie_przydzialow'] = "Taki przydział już istnieje!";
      $wszystko_ok = false;
    }

    //Jeśli wszystko poszło ok to dodaję przydział
    if ($wszystko_ok) {
      $sql = "INSERT INTO przydzial VALUES(NULL, '$wyb_nauczyciel', '$wyb_przedmiot', '$wyb_klasa')";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['dodawanie_przydzialow'] = "Nowy przydział został dodany!";
      else
        $_SESSION['dodawanie_przydzialow'] = "Nowy przydział nie został dodany!";
    }
  }

  header('Location: ../admin_przydzialy.php');

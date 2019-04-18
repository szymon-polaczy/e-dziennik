<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  //SALA JEST Z CZYMŚ POŁĄCZONA I NIE POWINIENEM MÓC JEJ USUNĄĆ
  if (isset($_GET['wyb_sala'])) {
    $wyb_sala = $_GET['wyb_sala'];
    $wszystko_ok = true;
    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //Testowanie czy sala nie jest przypisana do jakiegoś nauczyciela
    $sql = "SELECT id_osoba FROM nauczyciel WHERE id_sala='$wyb_sala'";

    $rezultat = $pdo->sql_table($sql);

    if (count($rezultat) > 0) {
      $_SESSION['usuwanie_sal'] = "Sala nie może zostać usunięta, ponieważ jest przypisana do nauczyciela!";
      $wszystko_ok = false;
    }

    if ($wszystko_ok) {
      $sql = "DELETE FROM sala WHERE id='$wyb_sala'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['usuwanie_sal'] = "Sala została usunięta!";
      else
        $_SESSION['usuwanie_sal'] = "Sala nie została usunięta!";
    }
  }

  header('Location: ../admin_sale.php');

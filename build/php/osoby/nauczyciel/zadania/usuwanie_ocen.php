<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_GET['wyb_przydzial']) || !isset($_GET['wyb_ocena'])) {
    header('Location: wybierz_przydzial.php');
    exit();
  }

  if (isset($_GET['wyb_ocena'])) {
    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $wyb_ocena = $_GET['wyb_ocena'];

    //usuwanie oceny
    $sql = "DELETE FROM ocena WHERE id='$wyb_ocena'";

    if ($rezultat = $pdo->sql_query($sql) > 0)
      $_SESSION['usuwanie_ocen'] = "Ocena została usunięta.";
    else
      $_SESSION['usuwanie_ocen'] = "Ocena nie została usunięta.";
  }

  header('Location: ../nauczyciel_oceny.php?wyb_przydzial='.$_GET['wyb_przydzial']);

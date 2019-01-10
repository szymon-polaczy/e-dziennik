<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (isset($_POST['wyb_wartosc'])) {
    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";
    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $wszystko_ok = true;

    $wyb_ocena = $_POST['wyb_ocena'];
    $wyb_wartosc = $_POST['wyb_wartosc'];

    //update oceny
    $sql = "UPDATE ocena SET wartosc='$wyb_wartosc', data=NULL WHERE id='$wyb_ocena'";

    if ($rezultat = $pdo->sql_query($sql) > 0)
      $_SESSION['edytowanie_ocen'] = "Ocena została edytowana.";
    else
      $_SESSION['edytowanie_ocen'] = "Ocena nie została edytowana.";
  }

  header('Location: ../nauczyciel_oceny.php?wyb_przydzial='.$_POST['wyb_przydzial']);

<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (!isset($_POST['wyb_przydzial'])) {
    header('Location: wybierz_przydzial.php');
    exit();
  }

  //DODAWANIE OCEN
  if (isset($_POST['wyb_uczen']) && isset($_POST['wyb_wartosc'])) {
    $wyb_uczen = $_POST['wyb_uczen'];
    $wyb_wartosc = $_POST['wyb_wartosc'];
    $wyb_przydzial = $_POST['wyb_przydzial'];

    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //wkładanie ocen do bazy danych
    $sql = "INSERT INTO ocena VALUES(NULL, '$wyb_przydzial', '$wyb_uczen', NULL, '$wyb_wartosc')";

    if ($rezultat = $pdo->sql_query($sql) > 0)
      $_SESSION['dodawanie_ocen'] = "Nowa ocena została dodana.";
    else
      $_SESSION['dodawanie_ocen'] = "Nowa ocena nie została dodana.";
  }

  header('Location: ../nauczyciel_oceny.php?wyb_przydzial='.$_POST['wyb_przydzial']);

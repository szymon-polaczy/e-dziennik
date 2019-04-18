<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(isset($_POST['nazwa'])) {
    $nazwa = $_POST['nazwa'];
    $wszystko_ok = true;

    require_once "../../../polacz.php";
    require_once "../../../wg_pdo_mysql.php";

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //Sprawdzanie długości nazwy
    if (strlen($nazwa) < 2 || strlen($nazwa) > 50) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_przedmiotow'] = "Nazwa przedmiotu musi mieć pomiędzy 2 a 50 znaków!";
    }

    //Sprawdzanie czy istnieje taka nazwa w bazie
    $sql = "SELECT id FROM przedmiot WHERE nazwa='$nazwa'";

    $rezultat = $pdo->sql_table($sql);

    if (count($rezultat) > 0) {
      $_SESSION['dodawanie_przedmiotow'] = "Przedmiot o takiej nazwie już istnieje!";
      $wszystko_ok = false;
    }

    //Po pozytywnym przejściu testów dodaję przedmiot
    if($wszystko_ok) {
      $sql = "INSERT INTO przedmiot VALUES(NULL, '$nazwa')";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['dodawanie_przedmiotow'] = "Nowy przedmiot został dodany.";
      else
        $_SESSION['dodawanie_przedmiotow'] = "Nowy przedmiot nie został dodany.";
    }
  }

  header('Location: ../admin_przedmioty.php');

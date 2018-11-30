<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  if (isset($_GET['wyb_osoba'])) {
    $wszystko_ok = true;
    $wyb_osoba = $_GET['wyb_osoba'];
    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    for ($i = 0; $i < $_SESSION['ilosc_osob']; $i++)
      if ($_SESSION['osoba'.$i]['id'] == $wyb_osoba) {
        $numer_osoby = $i;
        break;
      }

    //Test czy usuwasz samego siebie
    if ($_SESSION['id'] == $wyb_osoba) {
      $wszystko_ok = false;
      $_SESSION['usuwanie_osob'] = "Usuwasz sam siebie, wybierz kogoś innego!";
    }

    //Sprawdzam czy usuwasz jedynego administratora
    if ($_SESSION['osoba'.$numer_osoby]['uprawnienia'] == "a") {
      $ilosc_admin = 0;
      for ($i = 0; $i < $_SESSION['ilosc_osob']; $i++)
        if ($_SESSION['osoba'.$i]['uprawnienia'] == "a")
          if ($ilosc_admin++ > 2)
            break;

      if ($ilosc_admin < 2) {
        $wszystko_ok = false;
        $_SESSION['usuwanie_osob'] = "Usuwasz jedynego administratora, wybierz kogoś innego!";
      }
    }

    //testy czy usuwasz nauczyciela przypisanego do przydziału
    if ($_SESSION['osoba'.$numer_osoby]['uprawnienia'] == "n") {
      $sql = "SELECT * FROM przydzial WHERE id_nauczyciel='$wyb_osoba'";

      $rezultat = $pdo->sql_record($sql);

      if (count($rezultat) > 0) {
        $_SESSION['usuwanie_osob'] = "Ten nauczyciel jest przypisany do przydziałów, nie można go usunąć!";
        $wszystko_ok = false;
      }
    }

    //Sprawdzam czy jeśli jesteś uczniem to masz jakieś oceny
    if ($_SESSION['osoba'.$numer_osoby]['uprawnienia'] == "u") {
      $sql = "SELECT * FROM ocena WHERE ocena='$wyb_osoba'";

      $rezultat = $pdo->sql_record($sql);

      if (count($rezultat) > 0) {
        $_SESSION['usuwanie_osob'] = "Uczeń posiada oceny, nie można go usunąć!";
        $wszystko_ok = false;
      }
    }


    //Usuwanie po prostu osoby
    if ($wszystko_ok) {
      $numer_osoby = null;

      //Biorę i ogarniam numer osoby, nie id
      for ($i = 0; $i < $_SESSION['ilosc_osob']; $i++){
        if ($wyb_osoba == $_SESSION['osoba'.$i]['id'])
          $numer_osoby = $i;
      }

      //Usuwanie odpowiedniego zadania danej osoby
      $zadanie = "";

      switch ($_SESSION['osoba'.$numer_osoby]['uprawnienia']) {
        case 'a': $zadanie = "administrator"; break;
        case 'n': $zadanie = "nauczyciel"; break;
        case 'u': $zadanie = "uczen"; break;
      }

      $sql = "DELETE FROM `$zadanie` WHERE id_osoba='$wyb_osoba'";

      if ($rezultat = $pdo->sql_query($sql))
        $_SESSION['usuwanie_osob'] = "Zadanie osoby zostało usunięte!";
      else
        $_SESSION['usuwanie_osob'] = "Zadanie osoby nie zostało usunięte!";


      //Osoba
      $sql = "DELETE FROM osoba WHERE id='$wyb_osoba'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['usuwanie_osob'] = "Osoba została usunięta";
      else
        $_SESSION['usuwanie_osob'] = "Osoba nie została usunięta";
    }
  }

  header('Location: ../admin_osoby.php');

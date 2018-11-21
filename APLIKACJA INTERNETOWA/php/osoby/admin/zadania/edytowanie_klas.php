<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  if (isset($_POST['nazwa']) && isset($_POST['opis']) && isset($_POST['wyb_klasa'])) {
    $opis = $_POST['opis'];
    $nazwa = $_POST['nazwa'];
    $wyb_klasa = $_POST['wyb_klasa'];
    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    if (strlen($nazwa) > 0 && strlen($opis) > 0) {
      //------------------------------------DLA OBU
      $wszystko_ok = true;

      if(strlen($nazwa) < 2 || strlen($nazwa) > 20) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Nazwa musi mieć pomiędzy 2 a 20 znaków!";
      }

      if(strlen($opis) < 3 || strlen($opis) > 100) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Opis musi mieć pomiędzy 3 a 100 znaków!";
      }

      for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++) {
        if ($nazwa == $_SESSION['klasa'.$i]['nazwa']) {
          $wszystko_ok = false;
          $_SESSION['edytowanie_klas'] = "Klasa o takiej nazwie już istnieje!";
          break;
        }
      }

      //Jeśli wszystlo poszło ok to zmieniam zmieniam opis
      if ($wszystko_ok) {
        $sql = "UPDATE klasa SET nazwa='$nazwa', opis='$opis' WHERE nazwa='$wyb_klasa'";

        if ($rezultat = $pdo->sql_query($sql) > 0)
          $_SESSION['edytowanie_klas'] = "Klasa została edytowana!";
        else
          $_SESSION['edytowanie_klas'] = "Klasa nie została edytowana!";
      }
    } else if (strlen($nazwa) > 0) {
      //-----------------------------------DLA NAZWY
      $wszystko_ok = true;

      if(strlen($nazwa) < 2 || strlen($nazwa) > 20) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Nazwa musi mieć pomiędzy 2 a 20 znaków!";
      }

      for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++) {
        if ($nazwa == $_SESSION['klasa'.$i]['nazwa']) {
          $wszystko_ok = false;
          $_SESSION['edytowanie_klas'] = "Klasa o takiej nazwie już istnieje!";
          break;
        }
      }

      //Jeśli wszystlo poszło ok to zmieniam zmieniam nazwe
      if ($wszystko_ok) {
        $sql = "UPDATE klasa SET nazwa='$nazwa' WHERE nazwa='$wyb_klasa'";

        if ($rezultat = $pdo->sql_query($sql) > 0)
          $_SESSION['edytowanie_klas'] = "Nazwa została zmieniona!";
        else
          $_SESSION['edytowanie_klas'] = "Nazwa nie została zmieniona!";
      }
    } else if (strlen($opis) > 0) {
      //----------------------------------DLA OPISU
      $wszystko_ok = true;

      if(strlen($opis) < 3 || strlen($opis) > 100) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Opis musi mieć pomiędzy 3 a 100 znaków!";
      }

      //Jeśli wszystlo poszło ok to zmieniam zmieniam opis
      if ($wszystko_ok) {
        $sql = "UPDATE klasa SET opis='$opis' WHERE nazwa='$wyb_klasa'";

        if ($rezultat = $pdo->sql_query($sql) > 0)
          $_SESSION['edytowanie_klas'] = "Opis został zmieniony!";
        else
          $_SESSION['edytowanie_klas'] = "Opis nie został zmieniony!";
      }
    } else {
      $_SESSION['edytowanie_klas'] = "Wypełnij pola edycji!";
    }
  }

  header('Location: ../admin_klasy.php');
?>

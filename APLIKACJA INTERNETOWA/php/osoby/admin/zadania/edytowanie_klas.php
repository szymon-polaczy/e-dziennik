<?php
  session_start();

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  if (isset($_POST['nazwa']) && isset($_POST['opis'])) {
    $opis = $_POST['opis'];
    $nazwa = $_POST['nazwa'];
    $wyb_klasa = $_POST['wyb_klasa'];

    $wszystko_ok = true;
    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //Sprawdzam czy cokolwiek się zmieniło
    if ($opis != $orginalne['opis'] || $nazwa != $orginalne['nazwa']) {
      $wszystko_ok = false;
      $_SESSION['edytowanie_klas'] = "Nie nastąpiła żadna zmiana, klasa nie została zedytowana!";
    }

    //Wyciągam oryginalne wartości z bazy danych
    $sql = "SELECT nazwa, opis FROM klasa WHERE id='$wyb_klasa'";
    $orginalne = $pdo->sql_record($sql);

    if ($nazwa != $orginalne['nazwa']) {
      //Sprawdzam długośc nazwy
      if (strlen($nazwa) < 2 || strlen($nazwa) > 20) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Nazwa klasy musi mieć pomiędzy 2 a 20 znaków!";
      }

      //Sprawdzanie czy istnieje klasa o takiej nazwie w bazie danych
      $sql = "SELECT id FROM klasa WHERE nazwa='$nazwa'";

      $rezultat = $pdo->sql_field($sql);

      if (count($rezultat) > 0) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Klasa o takiej nazwie istnieje już w bazie, wybierz inną nazwę!";
      }
    }

    if ($opis != $orginalne['opis']) {
      //Sprawdzam długość opisu
      if (strlen($opis) < 3 || strlen($opis) > 100) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Opis klasy musi mieć pomiędzy 2 a 20 znaków!";
      }
    }

    //Jeśli wszystlo poszło ok to dodaję klasę
    if ($wszystko_ok) {
      $sql = "UPDATE klasa SET nazwa='$nazwa', opis='$opis' WHERE id='$wyb_klasa'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['edytowanie_klas'] = "Klasa została edytowana!";
      else
        $_SESSION['edytowanie_klas'] = "Klasa nie została edytowana!";
    }
  }

  header('Location: ../admin_klasy.php');

<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";


  if(isset($_POST['opis']) && isset($_POST['nazwa'])) {
    $wszystko_ok = true;

    $opis = htmlentities($_POST['opis'], ENT_QUOTES, "utf-8");
    $nazwa = htmlentities($_POST['nazwa'], ENT_QUOTES, "utf-8");

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //Sprawdzam długośc nazwy
    if (strlen($nazwa) < 2 || strlen($nazwa) > 20) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_klas'] = "Nazwa klasy musi mieć pomiędzy 2 a 20 znaków!";
    }

    //Sprawdzam długość opisu
    if (strlen($opis) < 3 || strlen($opis) > 100) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_klas'] = "Opis klasy musi mieć pomiędzy 2 a 20 znaków!";
    }

    //Sprawdzanie czy istnieje klasa o takiej nazwie w bazie danych
    $sql = "SELECT id FROM klasa WHERE nazwa='$nazwa'";

    $rezultat = $pdo->sql_field($sql);

    if (count($rezultat) > 0) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_klas'] = "Klasa o takiej nazwie istnieje już w bazie, wybierz inną nazwę!";
    }

    //Jeśli wszystlo poszło ok to dodaję klasę
    if ($wszystko_ok) {
      $sql = "INSERT INTO klasa VALUES(NULL, '$nazwa', '$opis')";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['dodawanie_klas'] = "Klasa została dodana!";
      else
        $_SESSION['dodawanie_klas'] = "Klasa nie została dodana!";
    }
  }

  header("Location:../admin_klasy.php");

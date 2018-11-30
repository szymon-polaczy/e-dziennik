<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  if (!isset($_POST['imie'])) {
    header('Location: ../edytowanie_osob.php');
    exit();
  }

  if (isset($_POST['imie'])) {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $email = $_POST['email'];
    $haslo = $_POST['haslo'];
    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $wszystko_ok = true;

    //TESTY IMIENIA - jeśli zmiana
    if ($imie != $_SESSION['edytowana']['imie']) {
      if(strlen($imie) <= 0 || strlen($imie) > 20) {
        $_SESSION['edytowanie_osob'] = "Imie osoby nie może być puste oraz musi być krótsze od 20 znaków!";
        $wszystko_ok = false;
      }
    }

    //TESTY NAZWISKA - jeśli zmiana
    if ($nazwisko != $_SESSION['edytowana']['nazwisko']) {
      if(strlen($nazwisko) <= 0 || strlen($nazwisko) > 30) {
        $_SESSION['edytowanie_osob'] = "Nazwisko osoby nie może być puste oraz musi być krótsze od 30 znaków!";
        $wszystko_ok = false;
      }
    }

    //TESTY EMAIL - jeśli zmiana
    if ($email != $_SESSION['edytowana']['email']) {
      if(strlen($email) <= 0 || strlen($email) > 255) {
        $_SESSION['edytowanie_osob'] = "Email osoby nie może być pusty oraz musi być krótszy od 255 znaków!";
        $wszystko_ok = false;
      }

      //Sprawdzanie poprawności adresu email
      $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
      if((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_osob'] = "Podaj poprawny nowy adres email!";
      }

      //Sprawdzanie czy nowy email nie jest już w bazie danych
      $sql = "SELECT id FROM osoba WHERE email='$emailB'";

      $rezultat = $pdo->sql_field($sql);

      if (count($rezultat) > 0) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_osob'] = "Taki adres email istnieje już w bazie!";
      }
    }

    //TESTY HASLO - jeśli zmiana
    if ($haslo != $_SESSION['edytowana']['haslo']) {
      if(strlen($haslo) < 8 || strlen($haslo) > 32) {
        $_SESSION['edytowanie_osob'] = "Hasło osoby musi posiadać pomiędzy 8 a 32 znakami!";
        $wszystko_ok = false;
      } else {
        //Z hasłem wszystko ok, wstawiam hash do hasła
        $hash = password_hash($haslo, PASSWORD_DEFAULT);
        $haslo = $hash;
      }
    }

    //FAKTYCZNE EDYTOWANIE OSOBY
    if ($wszystko_ok) {
      $edytowana_id = $_SESSION['edytowana']['id'];
      $sql = "UPDATE osoba SET imie='$imie', nazwisko='$nazwisko', email='$email', haslo='$haslo' WHERE id='$edytowana_id'";

      if ($rezultat = $pdo->sql_query($sql) >= 0)
        $_SESSION['edytowanie_osob'] = "Osoba została zedytowana";
    }
  }

  header('Location: ../admin_osoby.php');

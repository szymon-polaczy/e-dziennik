<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  //------------------------------------------------DODAWANIE OSÓB-----------------------------------------------//
  if (isset($_POST['imie']) || isset($_POST['nazwisko'])) {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $email = $_POST['email'];
    $haslo = $_POST['haslo'];
    $uprawnienia = $_POST['uprawnienia'];

    if ($uprawnienia == 'n')
      $wyb_sala = $_POST['wyb_sala'];
    else if ($uprawnienia == 'u') {
      $dataUrodzenia = $_POST['data_urodzenia'];
      $wyb_klasa = $_POST['wyb_klasa'];
    }

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //TESTY
    $wszystko_ok = true;

    if(strlen($imie) <= 0 || strlen($imie) > 20) {
      $_SESSION['dodawanie_osob'] = "Imie osoby nie może być puste oraz musi być krótsze od 20 znaków!";
      $wszystko_ok = false;
    }

    if(strlen($nazwisko) <= 0 || strlen($nazwisko) > 30) {
      $_SESSION['dodawanie_osob'] = "Nazwisko osoby nie może być puste oraz musi być krótsze od 30 znaków!";
      $wszystko_ok = false;
    }

    if(strlen($email) <= 0 || strlen($email) > 255) {
      $_SESSION['dodawanie_osob'] = "Email osoby nie może być pusty oraz musi być krótszy od 255 znaków!";
      $wszystko_ok = false;
    }

    //Sprawdzanie poprawności adresu email
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_osob'] = "Podaj poprawny nowy adres email!";
    }

    //Sprawdzanie czy nowy email nie jest już w bazie danych
    $sql = "SELECT id FROM osoba WHERE email='$emailB'";

    $rezultat = $pdo->sql_table($sql);

    if (count($rezultat) > 0) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_osob'] = "Taki adres email istnieje już w bazie!";
    }

    if(strlen($haslo) < 8 || strlen($haslo) > 32) {
      $_SESSION['dodawanie_osob'] = "Hasło osoby musi posiadać pomiędzy 8 a 32 znakami!";
      $wszystko_ok = false;
    }

    //Prosty test tak o
    if ($uprawnienia != "a" && $uprawnienia != "n" && $uprawnienia != "u") {
      $_SESSION['dodawanie_osob'] = "Uprawnenia zostały błędnie podane. Wybierz odpowienie uprawnienia!";
      $wszystko_ok = false;
    }

    //TESTY DLA UCZNIA I NAUCZYCIELA DODATKOWO
    if ($uprawnienia == "n") {
      //--CZY wyb_sala JEST W BAZIE
      $wyb_salaBaz = false;
      for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
        if ($wyb_sala == $_SESSION['sala'.$i]['id']) {
          $wyb_salaBaz = true;
          break;
        }
      }

      if ($wyb_salaBaz == false) {
        $_SESSION['dodawanie_osob'] = "Wybrana sala nie istenieje w bazie. Wybierz odpowiednią klasę!";
        $wszystko_ok = false;
      }
    } else if ($uprawnienia == "u") {
      //--CZY wyb_klasa JEST W BAZIE
      $wyb_klasaBaz = false;
      for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++) {
        if ($wyb_klasa == $_SESSION['klasa'.$i]['id']) {
          $wyb_klasaBaz = true;
          break;
        }
      }

      if ($wyb_klasaBaz == false) {
        $_SESSION['dodawanie_osob'] = "Wybrana klasa nie istenieje w bazie. Wybierz odpowiednią salę!";
        $wszystko_ok = false;
      }
    }




    //WKŁADANIE DO BAZY
    if ($wszystko_ok) {
      $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);

      //DODAWANIE OSOBY
      $sql = "INSERT INTO osoba VALUES (NULL, '$imie', '$nazwisko', '$email', '$haslo_hash', '$uprawnienia')";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['dodawanie_osob'] = "Nowa osoba została dodana!";
      else
        $_SESSION['dodawanie_osob'] = "Nowa osoba nie została dodana!";


      //WYCIĄGANIE ID NOWEJ OSOBY
      $sql = "SELECT id, haslo FROM osoba WHERE email='$email'";

      $rezultat = $pdo->sql_table($sql);

      if (count($rezultat) == 1 && password_verify($haslo, $rezultat[0]['haslo']))
        $nosoba_id = $rezultat[0]['id']; //sprawdz


      if ($uprawnienia == "a") {
        //DOŁOŻENIE ADMINISTRATORA
        $sql = "INSERT INTO administrator VALUES ('$nosoba_id')";

        if ($rezultat = $pdo->sql_query($sql) > 0)
          $_SESSION['dodawanie_osob'] = "Nowy administrator został dodany!";
        else
          $_SESSION['dodawanie_osob'] = "Nowy administrator nie został dodany!";
      } else if ($uprawnienia == "n") {
        //DOŁOŻENIE NAUCZYCIELA
        $sql = "INSERT INTO nauczyciel VALUES ('$nosoba_id', '$wyb_sala')";

        if ($rezultat = $pdo->sql_query($sql) > 0)
          $_SESSION['dodawanie_osob'] = "Nowy nauczyciel został dodany!";
        else
          $_SESSION['dodawanie_osob'] = "Nowy nauczyciel nie został dodany!";
      } else if ($uprawnienia == "u") {
        //DOŁOŻENIE UCZNIA
        $sql = "INSERT INTO uczen VALUES ('$nosoba_id', '$wyb_klasa', '$dataUrodzenia')";

        if ($rezultat = $pdo->sql_query($sql) > 0)
          $_SESSION['dodawanie_osob'] = "Nowy uczeń został dodany!";
        else
          $_SESSION['dodawanie_osob'] = "Nowy uczeń nie został dodany!";
      }
    }
  }

  header('Location: ../admin_osoby.php');

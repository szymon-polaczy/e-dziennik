<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_POST['email']) || !isset($_POST['haslo'])) {
    header('Location: ../index.php');
    exit();
  }

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  //Logowanie
  $email = htmlentities($_POST['email'], ENT_QUOTES, "utf-8");
  $haslo = htmlentities($_POST['haslo'], ENT_QUOTES, "utf-8");

  $sql = "SELECT * FROM osoba WHERE email='$email'";

  $rezultat = $pdo->sql_table($sql);

  if (count($rezultat) > 0) {
    if (password_verify($haslo, $rezultat[0]['haslo'])) {
      $_SESSION['id'] = $rezultat[0]['id'];
      $_SESSION['imie'] = $rezultat[0]['imie'];
      $_SESSION['nazwisko'] = $rezultat[0]['nazwisko'];
      $_SESSION['uprawnienia'] = $rezultat[0]['uprawnienia'];
      $_SESSION['email'] = $rezultat[0]['email'];
      $_SESSION['haslo'] = $rezultat[0]['haslo'];
      $_SESSION['zalogowany'] = true;
    } else {
      $_SESSION['login_blad'] = "Nie udało się zalogować, niepoprawny login lub hasło";
      header('Location: ../index.php');
    }
  } else {
    $_SESSION['login_blad'] = "Nie udało się zalogować, niepoprawny login lub hasło";
    header('Location: ../index.php');
  }

  //Jeśli się udało zalogować
  if (isset($_SESSION['zalogowany'])) {
    $moje_id = $_SESSION['id'];

    //Jeśli zalogowana osoba jest nauczycielem to wyciągam jego dane
    if ($_SESSION['uprawnienia'] == 'n') {
      $sql = "SELECT sala.nazwa AS sala_nazwa FROM nauczyciel, sala
              WHERE nauczyciel.id_osoba='$moje_id' AND sala.id=nauczyciel.id_sala";

      $_SESSION['sala_nazwa'] = $pdo->sql_value($sql);
    }

    //Czy jesteś uczniem
    if ($_SESSION['uprawnienia'] == 'u') {
      $sql = "SELECT uczen.data_urodzenia, klasa.nazwa AS klasa_nazwa, klasa.opis AS klasa_opis
              FROM uczen, klasa WHERE uczen.id_osoba='$moje_id' AND klasa.id=uczen.id_klasa";

      $rezultat = $pdo->sql_record($sql);

      $_SESSION['data_urodzenia'] = $rezultat['data_urodzenia'];
      $_SESSION['klasa_nazwa'] = $rezultat['klasa_nazwa'];
      $_SESSION['klasa_opis'] = $rezultat['klasa_opis'];
    }
    header('Location: ../dziennik.php');
  }

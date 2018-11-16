<?php
  session_start();

  if(!isset($_POST['email']) || !isset($_POST['haslo'])) {
    header('Location: ../index.php');
    exit();
  }

  require_once "../../../polacz.php";

  mysqli_report(MYSQLI_REPORT_STRICT);

  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      $email = $_POST['email'];
      $haslo = $_POST['haslo'];

      $email = htmlentities($email, ENT_QUOTES, "utf-8");

      $sql = sprintf("SELECT * FROM osoba WHERE email='%s'",
              mysqli_real_escape_string($polaczenie, $email));

      if ($rezultat = $polaczenie->query($sql)) {
        $ilu_userow = $rezultat->num_rows;
        if($ilu_userow == 1) {
          $wiersz = $rezultat->fetch_assoc();

          if (password_verify($haslo, $wiersz['haslo'])) {
            $_SESSION['id'] = $wiersz['id'];
            $_SESSION['imie'] = $wiersz['imie'];
            $_SESSION['nazwisko'] = $wiersz['nazwisko'];
            $_SESSION['uprawnienia'] = $wiersz['uprawnienia'];
            $_SESSION['email'] = $wiersz['email'];
            $_SESSION['haslo'] = $wiersz['haslo'];
            $_SESSION['zalogowany'] = true;

            $rezultat->free_result();

            header('Location: ../dziennik.php');
          } else {
            $_SESSION['login_blad'] = "Nie udało się zalogować, niepoprawny login lub hasło";
            header('Location: ../index.php');
          }
        } else {
          $_SESSION['login_blad'] = "Nie udało się zalogować, niepoprawny login lub hasło";
          header('Location: ../index.php');
        }
      } else {
        throw new Exception();
      }

      //Jeśli się udało zalogować
      if (isset($_SESSION['zalogowany']))
      {
        $moje_id = $_SESSION['id'];

        //Czy jesteś nauczycielem
        if ($_SESSION['uprawnienia'] == 'n') {
          //Wyciągam nauczyciela
          $sql = "SELECT nauczyciel.id_sala FROM nauczyciel WHERE nauczyciel.id_osoba='$moje_id'";

          if ($rezultat = $polaczenie->query($sql)) {
            $wiersz = $rezultat->fetch_assoc();

            $_SESSION['id_sala'] = $wiersz['id_sala'];

            $rezultat->free_result();
          } else {
            throw new Exception();
          }

          //Wyciągam salę
          $sala_id = $_SESSION['id_sala'];
          $sql = "SELECT sala.nazwa FROM sala WHERE sala.id='$sala_id'";

          if ($rezultat = $polaczenie->query($sql)) {
            $wiersz = $rezultat->fetch_assoc();

            $_SESSION['sala_nazwa'] = $wiersz['nazwa'];

            $rezultat->free_result();
          } else {
            throw new Exception();
          }
        }

        //Czy jesteś uczniem
        if ($_SESSION['uprawnienia'] == 'u') {
          //Wyciągam ucznia
          $sql = "SELECT uczen.id_klasa, uczen.data_urodzenia FROM uczen WHERE uczen.id_osoba='$moje_id'";

          if ($rezultat = $polaczenie->query($sql)) {
            $wiersz = $rezultat->fetch_assoc();

            $_SESSION['id_klasa'] = $wiersz['id_klasa'];
            $_SESSION['data_urodzenia'] = $wiersz['data_urodzenia'];

            $rezultat->free_result();
          } else {
            throw new Exception();
          }

          //Wyciągam klasę
          $klasa_id = $_SESSION['id_klasa'];
          $sql = "SELECT klasa.nazwa, klasa.opis FROM klasa WHERE klasa.id='$klasa_id'";

          if ($rezultat = $polaczenie->query($sql)) {
            $wiersz = $rezultat->fetch_assoc();

            $_SESSION['klasa_nazwa'] = $wiersz['nazwa'];
            $_SESSION['klasa_opis'] = $wiersz['opis'];

            $rezultat->free_result();
          } else {
            throw new Exception();
          }
        }
      }

      $polaczenie->close();
    } else {
      throw new Exception(mysqli_connect_errno());
    }
  } catch (Exception $blad){
    echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
    echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
  }

<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: index.php');
    exit();
  }

  require_once "../../polacz.php";

  mysqli_report(MYSQLI_REPORT_STRICT);

  //--------------------------------------------ZMIANA HASŁA-----------------------------------------------//
  if (isset($_POST['shaslo']) && isset($_POST['nhaslo']))
  {
    $wszystko_ok = true;

    $shaslo = $_POST['shaslo'];
    $nhaslo = $_POST['nhaslo'];

    //Sprawdzanie czy stare hasło zgadza się z prawdą
    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT haslo FROM osoba WHERE id='%s'",
                mysqli_real_escape_string($polaczenie, $_SESSION['id']));

        if ($rezultat = $polaczenie->query($sql)) {
          $wiersz = $rezultat->fetch_assoc();

          if (password_verify($shaslo, $wiersz['haslo'])) {
            $wszystko_ok = false;
            $_SESSION['zmiana_hasla'] = "Podaj poprawne stare hasło!";
          }

          $rezultat->free_result();
        } else {
          $wszystko_ok = false;
          throw new Exception();
        }
        $polaczenie->close();
      } else {
          throw new Exception(mysqli_connect_errno());
      }
    } catch (Exception $blad) {
      echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o próbę w innym terminie!</span>';
      echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
    }

    //Sprawdzanie długości hasła
    if (strlen($nhaslo) < 8 || strlen($nhaslo) > 32) {
      $wszystko_ok = false;
      $_SESSION['zmiana_hasla'] = "Hasło musi posiadać więcej niż 8 znaków oraz mniej niż 32!";
    }

    $haslo_hash = password_hash($nhaslo, PASSWORD_DEFAULT);

    //Jeśli wszystlo poszło ok to zmieniam hasło
    if ($wszystko_ok) {
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if ($polaczenie->connect_errno == 0) {
          $sql = sprintf("UPDATE osoba SET haslo='%s' WHERE id='%s'",
                  mysqli_real_escape_string($polaczenie, $haslo_hash),
                  mysqli_real_escape_string($polaczenie, $_SESSION['id']));

          if ($rezultat = $polaczenie->query($sql))
          {
            $_SESSION['zmiana_hasla'] = "Hasło zostało zmienione!";
          } else {
            throw new Exception();
          }
          $polaczenie->close();
        } else {
            throw new Exception(mysqli_connect_errno());
        }
      } catch (Exception $blad) {
        echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o próbę w innym terminie!</span>';
        echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
      }
    }
  }

  //--------------------------------------------ZMIANA EMAIL-----------------------------------------------//
  if (isset($_POST['semail']) && isset($_POST['nemail']))
  {
    $wszystko_ok = true;

    $semail = $_POST['semail'];
    $nemail = $_POST['nemail'];

    //Sprawdzanie czy stary email zgadza się z prawdą
    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT email FROM osoba WHERE id='%s'",
                mysqli_real_escape_string($polaczenie, $_SESSION['id']));

        if ($rezultat = $polaczenie->query($sql)) {
          $wiersz = $rezultat->fetch_assoc();

          if ($semail != $wiersz['email']) {
              $wszystko_ok = false;
              $_SESSION['zmiana_emailu'] = "Podaj poprawny stary adres email!";
          }

          $rezultat->free_result();
        } else {
          $wszystko_ok = false;
          throw new Exception();
        }
        $polaczenie->close();
      } else {
          throw new Exception(mysqli_connect_errno());
      }
    } catch (Exception $blad) {
      echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o próbę w innym terminie!</span>';
      echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
    }

    //Sprawdzanie poprawności adresu email
    $nemailB = filter_var($nemail, FILTER_SANITIZE_EMAIL);
    if((filter_var($nemailB, FILTER_VALIDATE_EMAIL) == false) || ($nemail != $nemailB)) {
      $wszystko_ok = false;
      $_SESSION['zmiana_emailu'] = "Podaj poprawny nowy adres email!";
    }

    //Sprawdzanie czy nowy email nie jest już w bazie danych
    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT email FROM osoba WHERE email='%s'",
                mysqli_real_escape_string($polaczenie, $nemailB));

        if ($rezultat = $polaczenie->query($sql))
        {
          $ile_emaili = $rezultat->num_rows;
          if ($ile_emaili > 0) {
            $wszystko_ok = false;
            $_SESSION['zmiana_emailu'] = "Taki adres email istnieje już w bazie!";
          }
        }
        $polaczenie->close();
      } else {
          throw new Exception(mysqli_connect_errno());
      }
    } catch (Exception $blad) {
      echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o próbę w innym terminie!</span>';
      echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
    }

    //Jeśli wszystko poszło ok to zmieniam adres email
    if ($wszystko_ok)
    {
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if ($polaczenie->connect_errno == 0) {
          $sql = sprintf("UPDATE osoba SET email='%s' WHERE id='%s'",
                  mysqli_real_escape_string($polaczenie, $nemail),
                  mysqli_real_escape_string($polaczenie, $_SESSION['id']));

          if ($rezultat = $polaczenie->query($sql))
          {
            $_SESSION['zmiana_emailu'] = "Email został zmieniony!";
          } else {
            throw new Exception();
          }
          $polaczenie->close();
        } else {
            throw new Exception(mysqli_connect_errno());
        }
      } catch (Exception $blad) {
        echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o próbę w innym terminiee!</span>';
        echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
      }
    }
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Zmień dane</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>
  <header>
    <h1>ZMIEŃ DANE</h1>
  </header>

  <main>
    <form method="post">
      <h3>Zmień Hasło</h3>
      <input type="password" placeholder="Stare hasło" name="shaslo">
      <input type="password" placeholder="Nowe hasło" name="nhaslo">
      <button type="submit">Zmień Hasło</button>
      <div class="info">
        <?php
          if (isset($_SESSION['zmiana_hasla'])) {
            echo '<p>'.$_SESSION['zmiana_hasla'].'</p>';
            unset($_SESSION['zmiana_hasla']);
          }
        ?>
      </div>
    </form>

    <form method="post">
      <h3>Zmień Email</h3>
      <input type="email" placeholder="Stary email" name="semail">
      <input type="email" placeholder="Nowy email" name="nemail">
      <button type="submit">Zmień Email</button>
      <div class="info">
        <?php
          if (isset($_SESSION['zmiana_emailu'])) {
            echo '<p>'.$_SESSION['zmiana_emailu'].'</p>';
            unset($_SESSION['zmiana_emailu']);
          }
        ?>
      </div>
    </form>
  </main>

  <footer class="fixed-bottom bg-dark glowna-stopka">
    <h6>Autor: Szymon Polaczy</h6>
  </footer>

  <a href="dziennik.php"><button class="cofnij-btn">Powrót do strony głównej</button></a>
</body>
</html>

<?php

  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);
  require_once "../../polacz.php";

  if(isset($_POST['opis']) && isset($_POST['nazwa'])) {

    $opis = htmlentities($_POST['opis'], ENT_QUOTES, "utf-8");
    $nazwa = htmlentities($_POST['nazwa'], ENT_QUOTES, "utf-8");
    $wszystko_ok = true;

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

    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if($polaczenie->connect_errno == 0) {

        //Sprawdzenie czy istnieje klasa o takiej nazwie w bazie
        $sql = sprintf("SELECT id FROM klasa WHERE nazwa='%s'",
                        mysqli_real_escape_string($polaczenie, $nazwa));

        if($rezultat = $polaczenie->query($sql)) {
          if ($rezultat->num_rows > 0) {
            $wszystko_ok = false;
            $_SESSION['dodawanie_klas'] = "Klasa o takiej nazwie już istnieje.";
          }
        } else {
          throw new Exception();
        }

        //Jeśli wszystko przebiegło odpowiednio to dodaję klasę do bazy
        if ($wszystko_ok) {
          $sql = sprintf("INSERT INTO klasa VALUES(NULL, '%s', '%s')",
                          mysqli_real_escape_string($polaczenie, $nazwa),
                          mysqli_real_escape_string($polaczenie, $opis));

          if($polaczenie->query($sql)) {
            $_SESSION['dodawanie_klas'] = "Dodano nową klasę";
            header("Location: ../../../adminklasy.php");
          } else {
            throw new Exception();
          }
        } else {
          header("Location: ../../../adminklasy.php");
        }

        $polaczenie->close();
      } else {
        throw new Exception(mysqli_connect_errno());
      }
    } catch (Exception $blad) {
      echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
      echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
    }
  }

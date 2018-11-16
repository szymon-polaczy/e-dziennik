<?php
  session_start();
  require_once "polacz.php";
  mysqli_report(MYSQLI_REPORT_STRICT);

  //WYCIĄGAM OSOBY
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      $sql = "SELECT * FROM osoba WHERE uprawnienia='n'";

      if ($rezultat = $polaczenie->query($sql)) {
        $ilosc_osob = $rezultat->num_rows;

        for ($i = 0; $i < $ilosc_osob; $i++) {
          $_SESSION['osoba'.$i] = $rezultat->fetch_assoc();
        }

        $rezultat->free_result();
      } else
          throw new Exception();

      $polaczenie->close();
    } else {
      throw new Exception(mysqli_connect_errno());
    }
  } catch (Exception $blad) {
    echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
    echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
  }

  //WYCIĄGAM NAUCZYCIELI
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      $sql = "SELECT * FROM nauczyciel";

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_nauczycieli'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_nauczycieli']; $i++) {
          $_SESSION['nauczyciel'.$i] = $rezultat->fetch_assoc();
        }

        //Dodaję osobę do wyświetlenia
        for ($i = 0; $i < $_SESSION['ilosc_nauczycieli']; $i++) {
          for ($j = 0; $j < $ilosc_osob; $j++) {
            if ($_SESSION['nauczyciel'.$i]['id_osoba'] == $_SESSION['osoba'.$j]['id']) {
              $_SESSION['nauczyciel'.$i]['imie'] = $_SESSION['osoba'.$j]['imie'];
              $_SESSION['nauczyciel'.$i]['nazwisko'] = $_SESSION['osoba'.$j]['nazwisko'];
            }
          }
        }

        $rezultat->free_result();
      } else
          throw new Exception();

      $polaczenie->close();
    } else {
      throw new Exception(mysqli_connect_errno());
    }
  } catch (Exception $blad) {
    echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
    echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
  }


  //WYCIĄGAM PRZEDMIOTY
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      $sql = "SELECT * FROM przedmiot";

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_przedmiotow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_nauczycieli']; $i++) {
          $_SESSION['przedmiot'.$i] = $rezultat->fetch_assoc();
        }

        //Dodaję przedmiot do wyświetlenia
        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
          for ($j = 0; $j < $_SESSION['ilosc_przedmiotow']; $j++)
            if ($_SESSION['przydzial'.$i]['id_przedmiot'] == $_SESSION['przedmiot'.$j]['id'])
              $_SESSION['przydzial'.$i]['przedmiot-nazwa'] = $_SESSION['przedmiot'.$j]['nazwa'];

        $rezultat->free_result();
      } else {
          throw new Exception();
      }
      $polaczenie->close();
    } else {
      throw new Exception(mysqli_connect_errno());
    }
  } catch (Exception $blad) {
    echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
    echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
  }

  //WYCIĄGAM KLASY
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      $sql = "SELECT * FROM klasa";

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_klas'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++) {
          $_SESSION['klasa'.$i] = $rezultat->fetch_assoc();
        }

        //Dodaję osobę do wyświetlenia
        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
          for ($j = 0; $j < $_SESSION['ilosc_klas']; $j++)
            if ($_SESSION['przydzial'.$i]['id_klasa'] == $_SESSION['klasa'.$j]['id'])
              $_SESSION['przydzial'.$i]['klasa-nazwa'] = $_SESSION['klasa'.$j]['nazwa'];

        $rezultat->free_result();
      } else
          throw new Exception();

      $polaczenie->close();
    } else
      throw new Exception(mysqli_connect_errno());
    }
  } catch (Exception $blad) {
    echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
    echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
  }





  //WYCIĄGANIE EDYTOWANEGO PRZYDZIAŁU
  if(!isset($_POST['wyb_przydzial']) && !isset($_POST['wyb_nauczyciel'])) {
    header('Location: adminprzydzialy.php');
    exit();
  } else if (isset($_POST['wyb_przydzial'])) {
    $wyb_przydzial = $_POST['wyb_przydzial'];

    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT * FROM przydzial WHERE id='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_przydzial));

        if ($rezultat = $polaczenie->query($sql)) {
          $wiersz = $rezultat->fetch_assoc();

          $_SESSION['edytowany_id'] = $wiersz['id'];
          $_SESSION['edytowany_id_nauczyciel'] = $wiersz['id_nauczyciel'];
          $_SESSION['edytowany_id_przedmiot'] = $wiersz['id_przedmiot'];
          $_SESSION['edytowany_id_klasa'] = $wiersz['id_klasa'];

          $rezultat->free_result();
        } else
          throw new Exception();

        $polaczenie->close();
      } else {
        throw new Exception(mysqli_connect_errno());
      }
    } catch (Exception $blad) {
      echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
      echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
    }
  }

  //EDYTOWANIE PRZYDZIAŁU
  if (isset($_POST['wyb_nauczyciel']) && isset($_POST['wyb_przedmiot']) && isset($_POST['wyb_klasa'])) {
    $wyb_nauczyciel = $_POST['wyb_nauczyciel'];
    $wyb_przedmiot = $_POST['wyb_przedmiot'];
    $wyb_klasa = $_POST['wyb_klasa'];

    $wszystko_ok = true;

    //Sprawdzam czy taki przydział już nie istnieje
    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT * FROM przydzial WHERE id_nauczyciel='%s'
                        AND id_przedmiot='%s' AND id_klasa='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_nauczyciel),
                        mysqli_real_escape_string($polaczenie, $wyb_przedmiot),
                        mysqli_real_escape_string($polaczenie, $wyb_klasa));

        if ($rezultat = $polaczenie->query($sql)) {
          if ($rezultat->num_rows > 0) {
            $wszystko_ok = false;
            $_SESSION['edytowanie_przydzialow'] = "Taki przydział już istnieje!";
          }
        } else
          throw new Exception();

        $polaczenie->close();
      } else {
        throw new Exception(mysqli_connect_errno());
      }
    } catch (Exception $blad) {
      echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
      echo '</br><span style="color: #c00">Informacja developerska: '.$blad.'</span>';
    }

    if ($wszystko_ok) {
      //Edytuję
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if ($polaczenie->connect_errno == 0) {
          $sql = sprintf("UPDATE przydzial SET id_nauczyciel='%s', id_przedmiot='%s',
                          id_klasa='%s' WHERE id='%s'",
                          mysqli_real_escape_string($polaczenie, $wyb_nauczyciel),
                          mysqli_real_escape_string($polaczenie, $wyb_klasa),
                          mysqli_real_escape_string($polaczenie, $_SESSION['edytowany_id']));

          if ($polaczenie->query($sql)) {
            $_SESSION['edytowanie_przydzialow'] = "Przydział został zedytowany";
          } else
            throw new Exception();

          $polaczenie->close();
        } else {
          throw new Exception(mysqli_connect_errno());
        }
      } catch (Exception $blad) {
        echo '<span style="color: #f33">Błąd serwera! Przepraszam za niedogodności i prosimy o powrót w innym terminie!</span>';
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

  <title>BDG DZIENNIK - Edytuj Przydział</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Redzik">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="index-body">
  <header>
    <h1>BDG DZIENNIK - edytowanie przydziału</h1>
  </header>

  <main>
    <form method="post">
      <h3>Edytuj Przydział</h3>
      <?php
        echo '<select name="wyb_nauczyciel">';

        for ($i = 0; $i < $_SESSION['ilosc_nauczycieli']; $i++) {
          if ($_SESSION['nauczyciel'.$i]['id_osoba'] == $_SESSION['edytowany_id_nauczyciel'])
            echo '<option selected value="'.$_SESSION['nauczyciel'.$i]['id_osoba'].'">Nauczyciel '.$_SESSION['nauczyciel'.$i]['imie'].' '.$_SESSION['nauczyciel'.$i]['nazwisko'].'</option>';
          else
            echo '<option value="'.$_SESSION['nauczyciel'.$i]['id_osoba'].'">Nauczyciel '.$_SESSION['nauczyciel'.$i]['imie'].' '.$_SESSION['nauczyciel'.$i]['nazwisko'].'</option>';
        }

        echo '</select>';
        echo '<select name="wyb_przedmiot">';

        for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++) {
          if ($_SESSION['przedmiot'.$i]['id'] == $_SESSION['edytowany_id_przedmiot'])
            echo '<option selected value="'.$_SESSION['przedmiot'.$i]['id'].'">Przedmiot '.$_SESSION['przedmiot'.$i]['nazwa'].'</option>';
          else
            echo '<option value="'.$_SESSION['przedmiot'.$i]['id'].'">Przedmiot '.$_SESSION['przedmiot'.$i]['nazwa'].'</option>';
        }

        echo '</select>';
        echo '<select name="wyb_klasa">';


        for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++) {
          if ($_SESSION['klasa'.$i]['id'] == $_SESSION['edytowany_id_klasa'])
            echo '<option selected value="'.$_SESSION['klasa'.$i]['id'].'">Klasa '.$_SESSION['klasa'.$i]['nazwa'].' | '.$_SESSION['klasa'.$i]['opis'].'</option>';
          else
            echo '<option value="'.$_SESSION['klasa'.$i]['id'].'">Klasa '.$_SESSION['klasa'.$i]['nazwa'].' | '.$_SESSION['klasa'.$i]['opis'].'</option>';
        }

        echo '</select>';


      ?>
      <button type="submit">Edytuj</button>
      <div class="info">
        <?php
          if (isset($_SESSION['edytowanie_przydzialow'])) {
            echo '<p>'.$_SESSION['edytowanie_przydzialow'].'</p>';
            unset($_SESSION['edytowanie_przydzialow']);
          }

        ?>
      </div>
    </form>
  </main>

  <a href="index.php"><button class="cofnij-btn">Wyjdź</button></a>

  <footer>
    <h6>Autor: Szymon Polaczy</h6>
  </footer>
</body>
</html>

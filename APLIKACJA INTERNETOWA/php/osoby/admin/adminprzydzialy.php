<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";
  mysqli_report(MYSQLI_REPORT_STRICT);

  //USUWAM PRZYDZIAŁY
  if (isset($_POST['wyb_przydzial'])){
    $wyb_przydzial = $_POST['wyb_przydzial'];

    $wszystko_ok = true;

    //Zabezpieczenie jeśli są jakieś oceny do danego przydziału
    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT * FROM ocena WHERE id_przydzial='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_przydzial));

        if ($rezultat = $polaczenie->query($sql)) {
          if ($rezultat->num_rows > 0) {
            $_SESSION['usuwanie_przydzialow'] = "Ten przydział jest powiązany z ocenami, nie można go usunąć!";
            $wszystko_ok = false;
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
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if ($polaczenie->connect_errno == 0) {
          $sql = sprintf("DELETE FROM przydzial WHERE id='%s'",
                          mysqli_real_escape_string($polaczenie, $wyb_przydzial));

          if ($polaczenie->query($sql))
            $_SESSION['usuwanie_przydzialow'] = "Przydział został usunięty!";
          else
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



  //DODAJĘ PRZYDZIAŁ
  if (isset($_POST['wyb_klasa']) && isset($_POST['wyb_przedmiot']) && isset($_POST['wyb_nauczyciel'])) {
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
            $_SESSION['dodawanie_przydzialow'] = "Taki przydział już istnieje!";
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
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if ($polaczenie->connect_errno == 0) {
          $sql = sprintf("INSERT INTO przydzial VALUES(NULL, '%s', '%s', '%s')",
                          mysqli_real_escape_string($polaczenie, $wyb_nauczyciel),
                          mysqli_real_escape_string($polaczenie, $wyb_przedmiot),
                          mysqli_real_escape_string($polaczenie, $wyb_klasa));

          if ($polaczenie->query($sql))
            $_SESSION['dodawanie_przydzialow'] = "Nowy przydział został dodany!";
          else
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



  //WYCIĄGAM PRZYDZIAŁY
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      $sql = "SELECT * FROM przydzial";

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_przydzialow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++) {
          $_SESSION['przydzial'.$i] = $rezultat->fetch_assoc();
        }

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

        //Dodaję osobę do wyświetlenia
        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++) {
          for ($j = 0; $j < $ilosc_osob; $j++) {
            if ($_SESSION['przydzial'.$i]['id_nauczyciel'] == $_SESSION['osoba'.$j]['id']) {
              $_SESSION['przydzial'.$i]['nauczyciel-id'] = $_SESSION['osoba'.$j]['id'];
              $_SESSION['przydzial'.$i]['nauczyciel-imie'] = $_SESSION['osoba'.$j]['imie'];
              $_SESSION['przydzial'.$i]['nauczyciel-nazwisko'] = $_SESSION['osoba'.$j]['nazwisko'];
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
            if ($_SESSION['przydzial'.$i]['id_przedmiot'] == $_SESSION['przedmiot'.$j]['id']) {
              $_SESSION['przydzial'.$i]['przedmiot-id'] = $_SESSION['przedmiot'.$j]['id'];
              $_SESSION['przydzial'.$i]['przedmiot-nazwa'] = $_SESSION['przedmiot'.$j]['nazwa'];
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
            if ($_SESSION['przydzial'.$i]['id_klasa'] == $_SESSION['klasa'.$j]['id']) {
              $_SESSION['przydzial'.$i]['klasa-id'] = $_SESSION['klasa'.$j]['id'];
              $_SESSION['przydzial'.$i]['klasa-nazwa'] = $_SESSION['klasa'.$j]['nazwa'];
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
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Dodaj, Usuń, Edytuj Przydziały</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body class="index-body">
  <header>
    <h1>DODAJ, USUŃ, EDYTUJ PRZYDZIAŁY</h1>
  </header>

  <main>
    <section>
      <form method="post">
        <h2>ZOBACZ PRZYDZIAŁY</h2>
        <?php
          if ($_SESSION['ilosc_przydzialow'] <= 0) {
            echo '<p>NIE MA ŻADNCH PRZYDZIAŁÓW, NAJPIERW DODAJ JAKIEŚ</p>';
          } else {
            echo '<table>';
            echo '<caption>PRZYDZIAŁY</caption>';

            echo '<tr>';
              echo '<th>ID</th>';
              echo '<th>IMIE NAUCZYCIELA</th>';
              echo '<th>NAZWISKO NAUCZYCIELA</th>';
              echo '<th>NAZWA PRZEDMIOTU</th>';
              echo '<th>NAZWA KLASY</th>';
            echo '</tr>';

            for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++) {
              echo '<tr>';
                echo '<th>'.$_SESSION['przydzial'.$i]['id'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['nauczyciel-imie'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['nauczyciel-nazwisko'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['przedmiot-nazwa'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['klasa-nazwa'].'</th>';
              echo '</tr>';
            }

            echo '</table>';
          }
        ?>
      </form>
    </section>
    <section>
      <form method="post">
        <h2>DODAJ PRZYDZIAŁY</h2>
        <?php
          if ($_SESSION['ilosc_nauczycieli'] <= 0 || $_SESSION['ilosc_przedmiotow'] <= 0 || $_SESSION['ilosc_klas'] <= 0) {
            echo '<div class="przydzial-wiersz" style="color: #f33">NIE MA NAUCZYCIELI LUB PRZEDMIOTÓW ALBO KLAS. DODAJ PIERW WSZYSTKIE ELEMENTY!</div>';
          } else {
            echo '<select name="wyb_nauczyciel">';

            for ($i = 0; $i < $_SESSION['ilosc_nauczycieli']; $i++)
              echo '<option value="'.$_SESSION['nauczyciel'.$i]['id_osoba'].'">Nauczyciel '.$_SESSION['nauczyciel'.$i]['imie'].' '.$_SESSION['nauczyciel'.$i]['nazwisko'].'</option>';

            echo '</select>';
            echo '<select name="wyb_przedmiot">';

            for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++)
              echo '<option value="'.$_SESSION['przedmiot'.$i]['id'].'">Przedmiot '.$_SESSION['przedmiot'.$i]['nazwa'].'</option>';

            echo '</select>';
            echo '<select name="wyb_klasa">';

            for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++)
              echo '<option value="'.$_SESSION['klasa'.$i]['id'].'">Klasa '.$_SESSION['klasa'.$i]['nazwa'].' | '.$_SESSION['klasa'.$i]['opis'].'</option>';

            echo '</select>';

            echo '<button type="submit">DODAJ</button>';

            echo '<div class="info">';
              if (isset($_SESSION['dodawanie_przydzialow'])) {
                echo '<p>'.$_SESSION['dodawanie_przydzialow'].'</p>';
                unset($_SESSION['dodawanie_przydzialow']);
              }
            echo '</div>';
          }
        ?>
      </form>
    </section>
    <section>
      <form method="post" action="edytowanieprzydzialow.php">
        <h2>EDYTUJ PRZYDZIAŁY</h2>
        <?php
          if ($_SESSION['ilosc_przydzialow'] <= 0) {
            echo '<div class="przydzial-wiersz" style="color: #f33">NIE MA ŻADNCH PRZYDZIAŁÓW, NAJPIERW DODAJ JAKIEŚ</div>';
          } else {
            echo '<select name="wyb_przydzial">';

            for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
              echo '<option value="'.$_SESSION['przydzial'.$i]['id'].'">'.$_SESSION['przydzial'.$i]['nauczyciel-imie']
              .' '.$_SESSION['przydzial'.$i]['nauczyciel-nazwisko']
              .' | '.$_SESSION['przydzial'.$i]['przedmiot-nazwa']
              .' | '.$_SESSION['przydzial'.$i]['klasa-nazwa'].'</option>';

            echo '</select>';

            echo '<button type="submit">WYBIERZ</button>';
          }
        ?>
      </form>
    </section>
    <section>
      <form method="post">
        <h2>USUŃ PRZYDZIAŁY</h2>
        <?php
          if ($_SESSION['ilosc_przydzialow'] <= 0) {
            echo '<div class="przydzial-wiersz" style="color: #f33">NIE MA ŻADNCH PRZYDZIAŁÓW, NAJPIERW DODAJ JAKIEŚ</div>';
          } else {
            echo '<select name="wyb_przydzial">';

            for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
              echo '<option value="'.$_SESSION['przydzial'.$i]['id'].'">'.$_SESSION['przydzial'.$i]['nauczyciel-imie']
              .' '.$_SESSION['przydzial'.$i]['nauczyciel-nazwisko']
              .' | '.$_SESSION['przydzial'.$i]['przedmiot-nazwa']
              .' | '.$_SESSION['przydzial'.$i]['klasa-nazwa'].'</option>';

            echo '</select>';

            echo '<button type="submit">USUŃ</button>';

            echo '<div class="info">';
              if (isset($_SESSION['usuwanie_przydzialow'])) {
                echo '<p>'.$_SESSION['usuwanie_przydzialow'].'</p>';
                unset($_SESSION['usuwanie_przydzialow']);
              }
            echo '</div>';
          }
        ?>
      </form>
    </section>
  </main>

  <a href="../wszyscy/dziennik.php"><button class="cofnij-btn">Powrót do strony głównej</button></a>

  <footer class="fixed-bottom bg-dark glowna-stopka">
    <h6>Autor: Szymon Polaczy</h6>
  </footer>
</body>
</html>

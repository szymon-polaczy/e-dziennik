<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: ../wszyscy/dziennik.php');
    exit();
  }

  require_once "../../polacz.php";
  mysqli_report(MYSQLI_REPORT_STRICT);

  //Wyciąganie przydziały do wyświetlenia
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      //NAUCZYCIEL, OSOBA, PRZYDZIAL
      $sql = "SELECT przydzial.id, przedmiot.nazwa, osoba.imie, osoba.nazwisko
              FROM osoba, nauczyciel, przydzial, przedmiot, klasa, uczen
              WHERE przydzial.id_nauczyciel=nauczyciel.id_osoba
              AND nauczyciel.id_osoba=osoba.id
              AND przydzial.id_przedmiot=przedmiot.id
              AND przydzial.id_klasa=klasa.id
              AND uczen.id_klasa=klasa.id";

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_przydzialow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
          $_SESSION['przydzial'.$i] = $rezultat->fetch_assoc();

        $rezultat->free_result();
      } else
          throw new Exception();

      //SALA
      $sql = "SELECT sala.nazwa
              FROM sala, osoba, nauczyciel, przydzial
              WHERE przydzial.id_nauczyciel=nauczyciel.id_osoba
              AND nauczyciel.id_osoba=osoba.id
              AND nauczyciel.id_sala=sala.id";

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_przydzialow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
          $_SESSION['przydzial'.$i]['sala'] = $rezultat->fetch_assoc();

        $rezultat->free_result();
      } else
          throw new Exception();

      //KLASA
      $sql = sprintf("SELECT klasa.nazwa
                      FROM osoba, uczen, klasa, przydzial
                      WHERE osoba.id='%s'
                      AND uczen.id_osoba=osoba.id
                      AND uczen.id_klasa=klasa.id
                      AND przydzial.id_klasa=klasa.id",
                      mysqli_real_escape_string($polaczenie, $_SESSION['id']));

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_przydzialow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
          $_SESSION['przydzial'.$i]['klasa'] = $rezultat->fetch_assoc();

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
              echo '<th>NAZWA PRZEDMIOTU</th>';
              echo '<th>NAZWA SALI</th>';
              echo '<th>NAZWA KLASY</th>';
              echo '<th>IMIE NAUCZYCIELA</th>';
              echo '<th>NAZWISKO NAUCZYCIELA</th>';
            echo '</tr>';

            for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++) {
              echo '<tr>';
                echo '<th>'.$_SESSION['przydzial'.$i]['id'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['nazwa'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['sala']['nazwa'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['klasa']['nazwa'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['imie'].'</th>';
                echo '<th>'.$_SESSION['przydzial'.$i]['nazwisko'].'</th>';
              echo '</tr>';
            }

            echo '</table>';
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

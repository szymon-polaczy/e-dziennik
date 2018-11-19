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

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
            echo '<table class="table">';

            echo '<thead class="thead-dark">';
              echo '<tr>';
                echo '<th scope="col">ID</th>';
                echo '<th scope="col">NAZWA PRZEDMIOTU</th>';
                echo '<th scope="col">NAZWA SALI</th>';
                echo '<th scope="col">NAZWA KLASY</th>';
                echo '<th scope="col">IMIE NAUCZYCIELA</th>';
                echo '<th scope="col">NAZWISKO NAUCZYCIELA</th>';
              echo '</tr>';
            echo '</thead>';

            echo '<tbody>';

            for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++) {
              echo '<tr>';
                echo '<td>'.$_SESSION['przydzial'.$i]['id'].'</td>';
                echo '<td>'.$_SESSION['przydzial'.$i]['nazwa'].'</td>';
                echo '<td>'.$_SESSION['przydzial'.$i]['sala']['nazwa'].'</td>';
                echo '<td>'.$_SESSION['przydzial'.$i]['klasa']['nazwa'].'</td>';
                echo '<td>'.$_SESSION['przydzial'.$i]['imie'].'</td>';
                echo '<td>'.$_SESSION['przydzial'.$i]['nazwisko'].'</td>';
              echo '</tr>';
            }

            echo '</tbody>';

            echo '</table>';
          }
        ?>
      </form>
    </section>

    <a href="../wszyscy/dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <footer class="fixed-bottom bg-dark glowna-stopka">
    <h6>Autor: Szymon Polaczy</h6>
  </footer>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

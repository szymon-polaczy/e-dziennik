<?php
  session_start();

  if (!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'u')) {
    header('Location: ../wszyscy/dziennik.php');
    exit();
  }

  require_once "../../polacz.php";
  mysqli_report(MYSQLI_REPORT_STRICT);

  //Wyciąganie ocen do wyświetlenia
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {

      $sql = sprintf("SELECT osoba.imie, osoba.nazwisko, przedmiot.nazwa, ocena.*
                      FROM osoba, nauczyciel, przydzial, przedmiot, uczen, ocena
                      WHERE uczen.id_osoba='%s'
                      AND ocena.id_uczen=uczen.id_osoba
                      AND ocena.id_przydzial=przydzial.id
                      AND przydzial.id_nauczyciel=nauczyciel.id_osoba
                      AND nauczyciel.id_osoba=osoba.id
                      AND przydzial.id_przedmiot=przedmiot.id",
                      mysqli_real_escape_string($polaczenie, $_SESSION['id']));

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_ocen'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++)
          $_SESSION['ocena'.$i] = $rezultat->fetch_assoc();

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

  <title>BDG DZIENNIK - Zobacz Oceny</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>
  <header>
    <h1>ZOBACZ OCENY</h1>
  </header>

  <main>
    <section>
      <?php
        if ($_SESSION['ilosc_ocen'] == 0) {
          echo '<p>Nie posiadasz żadnych ocen</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th>ID</th>';
              echo '<th>IMIE NAUCZYCIELA</th>';
              echo '<th>NAZWISKO NAUCZYCIELA</th>';
              echo '<th>NAZWA PRZEDMIOTU</th>';
              echo '<th>DATA</th>';
              echo '<th>WARTOŚĆ</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++) {
            echo '<tr>';
              echo '<td>'.$_SESSION['ocena'.$i]['id'].'</td>';
              echo '<td>'.$_SESSION['ocena'.$i]['imie'].'</td>';
              echo '<td>'.$_SESSION['ocena'.$i]['nazwisko'].'</td>';
              echo '<td>'.$_SESSION['ocena'.$i]['nazwa'].'</td>';
              echo '<td>'.$_SESSION['ocena'.$i]['data'].'</td>';
              echo '<td>'.$_SESSION['ocena'.$i]['wartosc'].'</td>';
            echo '</tr>';
          }

          echo '</tbody>';
          echo '</table>';
        }
      ?>
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

<?php
  session_start();

  if (!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'u')) {
    header('Location: dziennik.php');
    exit();
  }

  require_once "polacz.php";
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
  <meta name="author" content="Redzik">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
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
          echo '<div class="wiersz-ocena">';
            echo '<div>ID</div>';
            echo '<div>IMIE NAUCZYCIELA</div>';
            echo '<div>NAZWISKO NAUCZYCIELA</div>';
            echo '<div>NAZWA PRZEDMIOTU</div>';
            echo '<div>DATA</div>';
            echo '<div>WARTOŚĆ</div>';
          echo '</div>';

          for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++) {
            echo '<div class="wiersz-ocena">';
              echo '<div>'.$_SESSION['ocena'.$i]['id'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['imie'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['nazwisko'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['nazwa'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['data'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['wartosc'].'</div>';
            echo '</div>';
          }
        }
      ?>
    </section>
  </main>

  <footer>
    <h6>Autor: Szymon Polaczy</h6>
  </footer>

  <a href="index.php"><button class="cofnij-btn">Wyjdź</button></a>
</body>
</html>

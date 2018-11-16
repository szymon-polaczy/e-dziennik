<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: dziennik.php');
    exit();
  }

  require_once "polacz.php";
  mysqli_report(MYSQLI_REPORT_STRICT);

  //Wyciąganie przydziały do wyświetlenia
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {

      $sql = sprintf("SELECT klasa.nazwa, przydzial.id
                      FROM klasa, przydzial
                      WHERE przydzial.id_nauczyciel='%s'
                      AND przydzial.id_klasa=klasa.id",
                      mysqli_real_escape_string($polaczenie, $_SESSION['id']));

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_przydzialow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
          $_SESSION['przydzial'.$i] = $rezultat->fetch_assoc();

        $rezultat->free_result();
      } else
          throw new Exception();



      $sql = sprintf("SELECT przedmiot.nazwa
                      FROM przedmiot, przydzial
                      WHERE przydzial.id_nauczyciel='%s'
                      AND przydzial.id_przedmiot=przedmiot.id",
                      mysqli_real_escape_string($polaczenie, $_SESSION['id']));

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_przydzialow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
          $_SESSION['przydzial'.$i]['przedmiot'] = $rezultat->fetch_assoc();

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

  <title>BDG DZIENNIK - Zobacz Przydziały</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Redzik">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="index-body">
  <header>
    <h1>ZOBACZ PRZYDZIAŁY</h1>
  </header>

  <main>
    <section>
      <form method="post">
        <h2>ZOBACZ PRZYDZIAŁY</h2>
        <?php
          if ($_SESSION['ilosc_przydzialow'] <= 0) {
            echo '<div class="wiersz-przydzial" style="color: #f33">NIE MA ŻADNCH PRZYDZIAŁÓW, NAJPIERW DODAJ JAKIEŚ</div>';
          } else {
            echo '<div class="wiersz-przydzial"> ID | NAZWA PRZEDMIOTU | NAZWA KLASY </div>';
            for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++) {
              echo '<div class="wiersz-przydzial">';
                echo '<div>'.$_SESSION['przydzial'.$i]['id'].'</div>';
                echo '<div>'.$_SESSION['przydzial'.$i]['przedmiot']['nazwa'].'</div>';
                echo '<div>'.$_SESSION['przydzial'.$i]['nazwa'].'</div>';
              echo '</div>';
            }
          }
        ?>
      </form>
    </section>
  </main>

  <a href="index.php"><button class="cofnij-btn">Wyjdź</button></a>

  <footer>
    <h6>Autor: Szymon Polaczy</h6>
  </footer>
</body>
</html>

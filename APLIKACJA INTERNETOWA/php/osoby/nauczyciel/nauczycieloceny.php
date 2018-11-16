<?php
  session_start();

  if(!isset($_POST['wyb_przydzial']) && !isset($_POST['wyb_uczen']) && !isset($_POST['wyb_ocena'])) {
    header('Location: wybierzprzydzial.php');
    exit();
  }

  require_once "../../polacz.php";
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (isset($_POST['wyb_przydzial']))
    $_SESSION['wyb_przydzial'] = $_POST['wyb_przydzial'];

  //EDYTOWANIE OCEN
  if (isset($_POST['wyb_ocena']) && isset($_POST['wyb_wartosc'])) {
    $wyb_ocena = $_POST['wyb_ocena'];
    $wyb_wartosc = $_POST['wyb_wartosc'];

    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = sprintf("UPDATE ocena SET wartosc='%s', data=NULL WHERE id='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_wartosc),
                        mysqli_real_escape_string($polaczenie, $wyb_ocena));

        if ($polaczenie->query($sql)) {
          $_SESSION['edytowanie_ocen'] = "Ocena została zedytowana.";
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

  //USUWANIE OCEN
  if (isset($_POST['wyb_ocena']) && !isset($_POST['wyb_wartosc'])) {
    $wyb_ocena = $_POST['wyb_ocena'];

    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = sprintf("DELETE FROM ocena WHERE id='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_ocena));

        if ($polaczenie->query($sql)) {
          $_SESSION['usuwanie_ocen'] = "Ocena została usunięta";
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

  //DODAWANIE OCEN
  if (isset($_POST['wyb_uczen']) && isset($_POST['wyb_wartosc'])) {
    $wyb_uczen = $_POST['wyb_uczen'];
    echo $wyb_uczen;
    $wyb_wartosc = $_POST['wyb_wartosc'];
    $wyb_przydzial = $_SESSION['wyb_przydzial'];

    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {

        $sql = sprintf("INSERT INTO ocena VALUES(NULL, '%s', '%s', NULL, '%s')",
                        mysqli_real_escape_string($polaczenie, $_SESSION['wyb_przydzial']),
                        mysqli_real_escape_string($polaczenie, $wyb_uczen),
                        mysqli_real_escape_string($polaczenie, $wyb_wartosc));

        if ($polaczenie->query($sql)) {
          $_SESSION['dodawanie_ocen'] = "Nowa ocena została dodana.";
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


  //POBIERANIE UCZNIA DO WYŚWIETLENIA
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {

      //Pobieram osobę która jest uczniem, który jest w klasie, która jest przypisana do jakiegoś przydziału
      $sql = sprintf("SELECT osoba.imie, osoba.nazwisko, osoba.id FROM osoba, uczen, przydzial
                      WHERE osoba.uprawnienia='u' AND uczen.id_osoba=osoba.id
                      AND przydzial.id_klasa=uczen.id_klasa AND przydzial.id='%s'",
                      mysqli_real_escape_string($polaczenie, $_SESSION['wyb_przydzial']));

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_uczniow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_uczniow']; $i++)
          $_SESSION['uczen'.$i] = $rezultat->fetch_assoc();

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

  //Pobieranie ocen do wyświetlenia
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {

      $sql = sprintf("SELECT * FROM ocena WHERE ocena.id_przydzial='%s'",
                      mysqli_real_escape_string($polaczenie, $_SESSION['wyb_przydzial']));

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

  //Pobieranie Ucznia do wyświetlenia
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {

      $sql = sprintf("SELECT * FROM ocena, uczen, osoba WHERE ocena.id_przydzial='%s'
                      AND ocena.id_uczen=uczen.id_osoba AND uczen.id_osoba=osoba.id",
                      mysqli_real_escape_string($polaczenie, $_SESSION['wyb_przydzial']));

      if ($rezultat = $polaczenie->query($sql)) {

        for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++) {
          $wiersz = $rezultat->fetch_assoc();
          $_SESSION['ocena'.$i]['uczen-imie'] = $wiersz['imie'];
          $_SESSION['ocena'.$i]['uczen-nazwisko'] = $wiersz['nazwisko'];
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

  //Pobieranie Nauczyciela do wyświetlenia
  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {

      $sql = sprintf("SELECT * FROM ocena, przydzial, nauczyciel, osoba
                      WHERE ocena.id_przydzial='%s' AND ocena.id_przydzial=przydzial.id
                      AND przydzial.id_nauczyciel=nauczyciel.id_osoba
                      AND nauczyciel.id_osoba=osoba.id",
                      mysqli_real_escape_string($polaczenie, $_SESSION['wyb_przydzial']));

      if ($rezultat = $polaczenie->query($sql)) {

        for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++) {
          $wiersz = $rezultat->fetch_assoc();
          $_SESSION['ocena'.$i]['nauczyciel-imie'] = $wiersz['imie'];
          $_SESSION['ocena'.$i]['nauczyciel-nazwisko'] = $wiersz['nazwisko'];
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

  <title>BDG DZIENNIK - Zobacz, Dodaj, Usuń, Edytuj Oceny</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Redzik">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js" type="text/javascript"></script>
  <script src="../../../js/script.js"></script>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>
  <header>
    <h1>ZOBACZ, DODAJ, USUŃ, EDYTUJ OCENY</h1>
  </header>

  <main>
    <section>
      <form method="post">
        <h2>ZOBACZ OCENY</h2>
        <?php

        if ($_SESSION['ilosc_ocen'] == 0) {
          echo 'Nie ma żadnych ocen do wyświetlania';
        } else {
          echo '<div class="wiersz-ocena">';
            echo '<div>ID</div>';
            echo '<div>NAZWISKO NAUCZYCIELA</div>';
            echo '<div>IMIE UCZNIA</div>';
            echo '<div>NAZWISKO UCZNIA</div>';
            echo '<div>DATA I GODZINA</div>';
            echo '<div>WARTOŚĆ</div>';
          echo '</div>';

          for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++){
            echo '<div class="wiersz-ocena">';
              echo '<div>'.$_SESSION['ocena'.$i]['id'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['nauczyciel-nazwisko'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['uczen-imie'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['uczen-nazwisko'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['data'].'</div>';
              echo '<div>'.$_SESSION['ocena'.$i]['wartosc'].'</div>';
            echo '</div>';
          }
        }

        ?>
      </form>
    </section>
    <section>
      <form method="post">
        <h2>Dodaj ocenę</h2>

        <?php

        if ($_SESSION['ilosc_uczniow'] == 0) {
          echo 'Nie ma żadnych uczniów, którym mógłbyś dodać ocenę';
        } else {
          echo '<select name="wyb_uczen">';

          for ($i = 0; $i < $_SESSION['ilosc_uczniow']; $i++)
            echo '<option value="'.$_SESSION['uczen'.$i]['id'].'">'.$_SESSION['uczen'.$i]['imie'].' '.$_SESSION['uczen'.$i]['nazwisko'].'</option>';

          echo '</select>';


          $oceny = ['6', '6-', '5+', '5', '5-', '4+', '4', '4-', '3+', '3', '3-', '2+', '2', '2-', '1+', '1', '0'];

          echo '<select name="wyb_wartosc">';

          for ($i = 0; $i < count($oceny); $i++)
            echo '<option value="'.$oceny[$i].'">'.$oceny[$i].'</option>';

          echo '</select>';

          echo '<button type="submit">Dodaj ocenę</button>';

          if (isset($_SESSION['dodawanie_ocen'])) {
            echo '<p style="color: red">'.$_SESSION['dodawanie_ocen'].'</p>';
            unset($_SESSION['dodawanie_ocen']);
          }
        }

        ?>

      </form>
    </section>
    <section>
      <form method="post">
        <h2>EDYTUJ OCENY</h2>
        <?php

        if ($_SESSION['ilosc_ocen'] == 0) {
          echo 'Nie ma żadnych ocen do edycji';
        } else {
          echo '<select name="wyb_ocena" id="wyb_ocena_uzu"  onchange="pokazOdpOcene()">';

          for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++)
            echo '<option value="'.$_SESSION['ocena'.$i]['id'].'">'.$_SESSION['ocena'.$i]['nauczyciel-nazwisko']
            .' | '.$_SESSION['ocena'.$i]['uczen-imie'].' '.$_SESSION['ocena'.$i]['uczen-nazwisko'].' | Wartość: '.$_SESSION['ocena'.$i]['wartosc'].'</option>';

          echo '</select>';

          $oceny = ['6', '6-', '5+', '5', '5-', '4+', '4', '4-', '3+', '3', '3-', '2+', '2', '2-', '1+', '1', '0'];

          echo '<select name="wyb_wartosc" id="wyb_wartosc_uzu">';

          for ($i = 0; $i < count($oceny); $i++)
            echo '<option value="'.$oceny[$i].'">'.$oceny[$i].'</option>';

          echo '</select>';

          echo '<button type="submit">EDYTUJ</button>';

          if (isset($_SESSION['edytowanie_ocen'])) {
            echo '<p style="color: red">'.$_SESSION['edytowanie_ocen'].'</p>';
            unset($_SESSION['edytowanie_ocen']);
          }
        }

        ?>
      </form>
    </section>
    <section>
      <form method="post">
        <h2>USUŃ OCENY</h2>
        <?php

        if ($_SESSION['ilosc_ocen'] == 0) {
          echo 'Nie ma żadnych ocen do usunięcia';
        } else {
          echo '<select name="wyb_ocena">';

          for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++)
            echo '<option value="'.$_SESSION['ocena'.$i]['id'].'">'.$_SESSION['ocena'.$i]['nauczyciel-nazwisko']
            .' | '.$_SESSION['ocena'.$i]['uczen-imie'].' '.$_SESSION['ocena'.$i]['uczen-nazwisko'].' | '.$_SESSION['ocena'.$i]['wartosc'].'</option>';

          echo '</select>';

          echo '<button type="submit">USUŃ</button>';

          if (isset($_SESSION['usuwanie_ocen'])) {
            echo '<p style="color: red">'.$_SESSION['usuwanie_ocen'].'</p>';
            unset($_SESSION['usuwanie_ocen']);
          }
        }

        ?>
      </form>
    </section>
  </main>

  <footer>
    <h6>Autor: Szymon Polaczy</h6>
  </footer>

  <a href="../wszyscy/dziennik.php"><button class="cofnij-btn">Wyjdź</button></a>
</body>
</html>

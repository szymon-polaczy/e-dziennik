<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";

  mysqli_report(MYSQLI_REPORT_STRICT);

  //---------------------------------------------------USUWANIE KLASY--------------------------------------------------------//
  if (isset($_POST['wyb_klasa']) && !isset($_POST['nazwa'])) {
    $wyb_klasa = $_POST['wyb_klasa'];

    $wszystko_ok = true;

    //Sprawdzanie czy dana klasa jest w jakimś przydziale
    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT * FROM przydzial WHERE id_klasa='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_klasa));

        if($rezultat = $polaczenie->query($sql)) {
          if ($rezultat->num_rows > 0) {
            $_SESSION['usuwanie_klas'] = "Nie można usunąć danej klasy, ponieważ jest połączona z przydziałem";
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

    //Sprawdzenie czy klasa jest przypisana do ucznia
    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT * FROM uczen WHERE id_klasa='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_klasa));

        if($rezultat = $polaczenie->query($sql)) {
          if ($rezultat->num_rows > 0) {
            $_SESSION['usuwanie_klas'] = "Nie można usunąć danej klasy, ponieważ jest połączona z uczniem";
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

        if($polaczenie->connect_errno == 0) {
          $sql = sprintf("DELETE FROM klasa WHERE id='%s'",
                          mysqli_real_escape_string($polaczenie, $wyb_klasa));

          if($polaczenie->query($sql))
            $_SESSION['usuwanie_klas'] = "Klasa została usunięta";
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



  //------------------------------------------------WYCIĄGANIE KLAS DO OBEJRZENIA-----------------------------------------------//

  function wezKlasy() {
    try {
      $polaczenie = new mysqli("localhost", "root", "<kizdeR<", "bdg_dziennik");
      $polaczenie->query("SET NAMES utf8");

      if ($polaczenie->connect_errno == 0) {
        $sql = "SELECT * FROM klasa";

        if ($rezultat = $polaczenie->query($sql)) {
          $_SESSION['ilosc_klas'] = $rezultat->num_rows;

          for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++)
            $_SESSION['klasa'.$i] = $rezultat->fetch_assoc();

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

  wezKlasy();

  //-----------------------------------------------------DODAWANIE KLAS------------------------------------------------------//
  if (isset($_POST['nazwa']) && isset($_POST['opis']) && isset($_POST['wyb_klasa'])) {
    $wyb_klasa = $_POST['wyb_klasa'];
    $nazwa = $_POST['nazwa'];
    $opis = $_POST['opis'];

    if (strlen($nazwa) > 0 && strlen($opis) > 0) {
      //------------------------------------DLA OBU
      $wszystko_ok = true;

      if(strlen($nazwa) < 2 || strlen($nazwa) > 20) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Nazwa musi mieć pomiędzy 2 a 20 znaków!";
      }

      if(strlen($opis) < 3 || strlen($opis) > 100) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Opis musi mieć pomiędzy 3 a 100 znaków!";
      }

      for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++) {
        if ($nazwa == $_SESSION['klasa'.$i]['nazwa']) {
          $wszystko_ok = false;
          $_SESSION['edytowanie_klas'] = "Klasa o takiej nazwie już istnieje!";
          break;
        }
      }

      if ($wszystko_ok) {
        try {
          $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
          $polaczenie->query("SET NAMES utf8");

          if($polaczenie->connect_errno == 0) {
            $sql = sprintf("UPDATE klasa SET nazwa='%s', opis='%s' WHERE nazwa='%s'",
                            mysqli_real_escape_string($polaczenie, $nazwa),
                            mysqli_real_escape_string($polaczenie, $opis),
                            mysqli_real_escape_string($polaczenie, $wyb_klasa));

            if($polaczenie->query($sql))
              $_SESSION['edytowanie_klas'] = "Klasa została edytowana";
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
    } else if (strlen($nazwa) > 0) {
      //-----------------------------------DLA NAZWY
      $wszystko_ok = true;

      if(strlen($nazwa) < 2 || strlen($nazwa) > 20) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Nazwa musi mieć pomiędzy 2 a 20 znaków!";
      }

      for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++) {
        if ($nazwa == $_SESSION['klasa'.$i]['nazwa']) {
          $wszystko_ok = false;
          $_SESSION['edytowanie_klas'] = "Klasa o takiej nazwie już istnieje!";
          break;
        }
      }

      if ($wszystko_ok) {
        try {
          $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
          $polaczenie->query("SET NAMES utf8");

          if($polaczenie->connect_errno == 0) {
            $sql = sprintf("UPDATE klasa SET nazwa='%s' WHERE nazwa='%s'",
                            mysqli_real_escape_string($polaczenie, $nazwa),
                            mysqli_real_escape_string($polaczenie, $wyb_klasa));

            if($polaczenie->query($sql))
              $_SESSION['edytowanie_klas'] = "Klasa została edytowana";
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
    } else if (strlen($opis) > 0) {
      //----------------------------------DLA OPISU
      $wszystko_ok = true;

      if(strlen($opis) < 3 || strlen($opis) > 100) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_klas'] = "Opis musi mieć pomiędzy 3 a 100 znaków!";
      }

      if ($wszystko_ok) {
        try {
          $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
          $polaczenie->query("SET NAMES utf8");

          if($polaczenie->connect_errno == 0) {
            $sql = sprintf("UPDATE klasa SET opis='%s' WHERE nazwa='%s'",
                            mysqli_real_escape_string($polaczenie, $opis),
                            mysqli_real_escape_string($polaczenie, $wyb_klasa));

            if($polaczenie->query($sql))
              $_SESSION['edytowanie_klas'] = "Opis klasy został zedytowany";
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
    } else {
      $_SESSION['edytowanie_klas'] = "Wypełnij pola edycji!";
    }
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Zobacz, Dodaj, Usuń, Edytuj Sale</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>
  <header>
    <h1>ZOBACZ, DODAJ, USUŃ, EDYTUJ KLASY</h1>
  </header>

  <main>
    <section>
      <h2>ZOBACZ KLASY</h2>
      <?php
        if ($_SESSION['ilosc_klas'] == 0) {
          echo '<p>ŻADNA KLASA NIE ISTNIEJE W BAZIE</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th>NUMER</div>';
              echo '<th>ID</th>';
              echo '<th>NAZWA</th>';
              echo '<th>OPIS</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++) {
            echo '<tr>';
              echo '<td>'.$i.'</td>';
              echo '<td>'.$_SESSION['klasa'.$i]['id'].'</td>';
              echo '<td>'.$_SESSION['klasa'.$i]['nazwa'].'</td>';
              echo '<td>'.$_SESSION['klasa'.$i]['opis'].'</td>';
            echo '</tr>';
          }

          echo '</tbody>';
          echo '</table>';
        }
      ?>
    </section>
    <section>
      <form method="post" action="zadania/dodawanie_klas.php">
        <h3>DODAJ KLASĘ</h3>
        <input type="text" placeholder="Nazwa" name="nazwa"/>
        <input type="text" placeholder="Opis" name="opis"/>
        <button type="submit">Dodaj</button>
        <div class="info">
          <?php
            if (isset($_SESSION['dodawanie_klas'])) {
              echo '<p>'.$_SESSION['dodawanie_klas'].'</p>';
              unset($_SESSION['dodawanie_klas']);
            }
          ?>
        </div>
      </form>
    </section>
    <section>
      <form method="post">
        <h3>EDYTUJ KLASĘ</h3>
        <select name="wyb_klasa">
          <?php
            for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++)
              echo '<option value="'.$_SESSION['klasa'.$i]['nazwa'].'">'.$_SESSION['klasa'.$i]['nazwa'].'</option>';
          ?>
        </select>
        <input type="text" placeholder="Nazwa" name="nazwa"/>
        <input type="text" placeholder="Opis" name="opis"/>
        <button type="submit">Edytuj</button>
        <div class="info">
          <?php
            if (isset($_SESSION['edytowanie_klas'])) {
              echo '<p>'.$_SESSION['edytowanie_klas'].'</p>';
              unset($_SESSION['edytowanie_klas']);
            }
          ?>
        </div>
      </form>
    </section>
    <section>
      <form method="post">
        <h3>USUŃ KLASĘ</h3>
        <select name="wyb_klasa">
          <?php
            for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++)
              echo '<option value="'.$_SESSION['klasa'.$i]['id'].'">'.$_SESSION['klasa'.$i]['nazwa'].'</option>';
          ?>
        </select>
        <button type="submit">Usuń</button>
        <div class="info">
          <?php
            if (isset($_SESSION['usuwanie_klas'])) {
              echo '<p>'.$_SESSION['usuwanie_klas'].'</p>';
              unset($_SESSION['usuwanie_klas']);
            }
          ?>
        </div>
      </form>
    </section>
  </main>

  <footer class="fixed-bottom bg-dark glowna-stopka">
    <h6>Autor: Szymon Polaczy</h6>
  </footer>

  <a href="../wszyscy/dziennik.php"><button class="cofnij-btn">Powrót do strony głównej</button></a>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

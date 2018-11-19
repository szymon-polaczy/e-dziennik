<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";

  mysqli_report(MYSQLI_REPORT_STRICT);

  //------------------------------------------------WYCIĄGANIE SAL DO OBEJRZENIA-----------------------------------------------//

  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      $sql = "SELECT * FROM sala";

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_sal'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
          $_SESSION['sala'.$i] = $rezultat->fetch_assoc();
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

  //-----------------------------------------------------DODAWANIE SAL------------------------------------------------------//
  if(isset($_POST['nazwa']) && !isset($_POST['wyb_sala'])) {
    $nazwa = $_POST['nazwa'];
    $wszystko_ok = true;

    //Sprawdzanie długości nazwy
    if (strlen($nazwa) < 2 || strlen($nazwa) > 20) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_sal'] = "Nazwa sali musi mieć pomiędzy 2 a 20 znaków!";
    }

    //Sprawdzanie czy istnieje taka nazwa w bazie
    for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
      if ($nazwa == $_SESSION['sala'.$i]['nazwa']) {
        $wszystko_ok = false;
        $_SESSION['dodawanie_sal'] = "Sala o takiej nazwie już istnieje!";
        break;
      }
    }

    //Po pozytywnym przejściu testów dodaję salę
    if($wszystko_ok) {
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if($polaczenie->connect_errno == 0) {
          $sql = sprintf("INSERT INTO sala VALUES (NULL, '%s')",
                          mysqli_real_escape_string($polaczenie, $nazwa));

          if($polaczenie->query($sql)) {
            $_SESSION['dodawanie_sal'] = "Nowa sala została dodana!";
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
    }
  }

  //-----------------------------------------------------EDYTOWANIE SAL------------------------------------------------------//
  if (isset($_POST['nazwa']) && isset($_POST['wyb_sala'])) {
    $wyb_sala = $_POST['wyb_sala'];
    $nazwa = $_POST['nazwa'];
    $wszystko_ok = true;

    if(strlen($nazwa) < 2 || strlen($nazwa) > 20) {
      $wszystko_ok = false;
      $_SESSION['edytowanie_sal'] = "Nazwa musi mieć pomiędzy 2 a 20 znaków!";
    }

    for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
      if ($nazwa == $_SESSION['sala'.$i]['nazwa']) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_sal'] = "Sala o takiej nazwie już istnieje!";
        break;
      }
    }

    if ($wszystko_ok) {
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if($polaczenie->connect_errno == 0) {
          $sql = sprintf("UPDATE sala SET nazwa='%s' WHERE nazwa='%s'",
                          mysqli_real_escape_string($polaczenie, $nazwa),
                          mysqli_real_escape_string($polaczenie, $wyb_sala));

          if($polaczenie->query($sql)) {
            $_SESSION['edytowanie_sal'] = "Nazwa sali została zedytowana";
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

  //---------------------------------------------------USUWANIE SAL--------------------------------------------------------//
  if (isset($_POST['wyb_sala']) && !isset($_POST['nazwa'])) {
    $wyb_sala = $_POST['wyb_sala'];

    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if($polaczenie->connect_errno == 0) {
        $sql = sprintf("DELETE FROM sala WHERE nazwa='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_sala));

        if($polaczenie->query($sql)) {
          $_SESSION['usuwanie_sal'] = "Sala została usunięta!";
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
    <h1>ZOBACZ, DODAJ, USUŃ, EDYTUJ SALI</h1>
  </header>

  <main>
    <section>
      <h2>ZOBACZ SALE</h2>
      <?php
        if ($_SESSION['ilosc_sal'] == 0) {
          echo '<p>ŻADNA SALA NIE ISTNIEJE W BAZIE</p>';
        } else {
          echo '<table>';
          echo '<caption>SALA</caption>';

          echo '<tr>';
            echo '<th>NUMER</th>';
            echo '<th>ID</th>';
            echo '<th>NAZWA</th>';
          echo '</tr>';

          for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
            echo '<tr>';
              echo '<th>'.$i.'</th>';
              echo '<th>'.$_SESSION['sala'.$i]['id'].'</th>';
              echo '<th>'.$_SESSION['sala'.$i]['nazwa'].'</th>';
            echo '</tr>';
          }

          echo '</table>';
        }

      ?>
    </section>
    <section>
      <form method="post">
        <h3>DODAJ SALE</h3>
        <input type="text" placeholder="Nazwa" name="nazwa"/>
        <button type="submit">Dodaj</button>
        <div class="info">
          <?php
            if (isset($_SESSION['dodawanie_sal'])) {
              echo '<p>'.$_SESSION['dodawanie_sal'].'</p>';
              unset($_SESSION['dodawanie_sal']);
            }
          ?>
        </div>
      </form>
    </section>
    <section>
      <form method="post">
        <h3>EDYTUJ SALE</h3>
        <select name="wyb_sala">
          <?php
            for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++)
              echo '<option value="'.$_SESSION['sala'.$i]['nazwa'].'">'.$_SESSION['sala'.$i]['nazwa'].'</option>';
          ?>
        </select>
        <input type="text" placeholder="Nazwa" name="nazwa"/>
        <button type="submit">Edytuj</button>
        <div class="info">
          <?php
            if (isset($_SESSION['edytowanie_sal'])) {
              echo '<p>'.$_SESSION['edytowanie_sal'].'</p>';
              unset($_SESSION['edytowanie_sal']);
            }
          ?>
        </div>
      </form>
    </section>
    <section>
      <form method="post">
        <h3>USUŃ SALE</h3>
        <select name="wyb_sala">
          <?php
            for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++)
              echo '<option value="'.$_SESSION['sala'.$i]['nazwa'].'">'.$_SESSION['sala'.$i]['nazwa'].'</option>';
          ?>
        </select>
        <button type="submit">Usuń</button>
        <div class="info">
          <?php
            if (isset($_SESSION['usuwanie_sal'])) {
              echo '<p>'.$_SESSION['usuwanie_sal'].'</p>';
              unset($_SESSION['usuwanie_sal']);
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

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
      <div class="wiersz-sala">
        <div class="kolumna-numer">NUMER</div>
        <div class="kolumna-id">ID</div>
        <div class="kolumna-nazwa">NAZWA</div>
      </div>
      <?php
        for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
          echo '<div class="wiersz-sala">';
            echo '<div class="kolumna kolumna-numer">'.$i.'</div>';
            echo '<div class="kolumna kolumna-id">'.$_SESSION['sala'.$i]['id'].'</div>';
            echo '<div class="kolumna kolumna-nazwa">'.$_SESSION['sala'.$i]['nazwa'].'</div>';
          echo '</div>';
        }
        if ($_SESSION['ilosc_sal'] == 0) {
          echo '<div class="wiersz-sala">ŻADNA SALA NIE ISTNIEJE W BAZIE</div>';
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
</body>
</html>

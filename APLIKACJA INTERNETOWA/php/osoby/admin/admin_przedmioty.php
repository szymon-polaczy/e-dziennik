<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";

  mysqli_report(MYSQLI_REPORT_STRICT);

  //---------------------------------------------------USUWANIE przedmiotu--------------------------------------------------------//
  if (isset($_POST['wyb_przedmiot']) && !isset($_POST['nazwa'])) {
    $wyb_przedmiot = $_POST['wyb_przedmiot'];
    $wszystko_ok = true;

    //Sprawdzam czy przedmiot nie jest gdzieś w jakimś przydziale
    try {
      $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
      $polaczenie->query("SET NAMES utf8");

      if($polaczenie->connect_errno == 0) {
        $sql = sprintf("SELECT * FROM przydzial WHERE id_przedmiot='%s'",
                        mysqli_real_escape_string($polaczenie, $wyb_przedmiot));

        if($rezultat = $polaczenie->query($sql)) {
          if ($rezultat->num_rows > 0) {
            $_SESSION['usuwanie_przedmiotow'] = "Przedmiot jest przypisany do przydziałów, nie można go usunąć!";
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
          $sql = sprintf("DELETE FROM przedmiot WHERE id='%s'",
                          mysqli_real_escape_string($polaczenie, $wyb_przedmiot));

          if($polaczenie->query($sql)) {
            $_SESSION['usuwanie_przedmiotow'] = "Przedmiot został usunięty!";
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



  //------------------------------------------------WYCIĄGANIE PRZEDMIOTÓW DO OBEJRZENIA-----------------------------------------------//

  try {
    $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
    $polaczenie->query("SET NAMES utf8");

    if ($polaczenie->connect_errno == 0) {
      $sql = "SELECT * FROM przedmiot";

      if ($rezultat = $polaczenie->query($sql)) {
        $_SESSION['ilosc_przedmiotow'] = $rezultat->num_rows;

        for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++)
          $_SESSION['przedmiot'.$i] = $rezultat->fetch_assoc();

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

  //-----------------------------------------------------DODAWANIE KLAS------------------------------------------------------//
  if(isset($_POST['nazwa']) && !isset($_POST['wyb_przedmiot'])) {
    $nazwa = $_POST['nazwa'];
    $wszystko_ok = true;

    //Sprawdzanie długości nazwy
    if (strlen($nazwa) < 2 || strlen($nazwa) > 50) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_przedmiotow'] = "Nazwa przedmiotu musi mieć pomiędzy 2 a 50 znaków!";
    }

    //Sprawdzanie czy istnieje taka nazwa w bazie
    for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++) {
      if ($nazwa == $_SESSION['przedmiot'.$i]['nazwa']) {
        $wszystko_ok = false;
        $_SESSION['dodawanie_przedmiotow'] = "Przedmiot o takiej nazwie już istnieje!";
        break;
      }
    }

    //Po pozytywnym przejściu testów dodaję przedmiot
    if($wszystko_ok) {
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if($polaczenie->connect_errno == 0) {
          $sql = sprintf("INSERT INTO przedmiot VALUES(NULL, '%s')",
                          mysqli_real_escape_string($polaczenie, $nazwa));

          if($polaczenie->query($sql))
            $_SESSION['dodawanie_przedmiotow'] = "Nowy przedmiot został dodany!";
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

  //-----------------------------------------------------DODAWANIE KLAS------------------------------------------------------//
  if (isset($_POST['nazwa']) && isset($_POST['wyb_przedmiot'])) {
    $wyb_przedmiot = $_POST['wyb_przedmiot'];
    $nazwa = $_POST['nazwa'];
    $wszystko_ok = true;

    if(strlen($nazwa) < 2 || strlen($nazwa) > 50) {
      $wszystko_ok = false;
      $_SESSION['edytowanie_przedmiotow'] = "Nazwa musi mieć pomiędzy 2 a 50 znaków!";
    }

    for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++) {
      if ($nazwa == $_SESSION['przedmiot'.$i]['nazwa']) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_przedmiotow'] = "Przedmiot o takiej nazwie już istnieje!";
        break;
      }
    }

    if ($wszystko_ok) {
      try {
        $polaczenie = new mysqli($host, $bd_uzytk, $bd_haslo, $bd_nazwa);
        $polaczenie->query("SET NAMES utf8");

        if($polaczenie->connect_errno == 0) {
          $sql = sprintf("UPDATE przedmiot SET nazwa='%s' WHERE nazwa='%s'",
                          mysqli_real_escape_string($polaczenie, $nazwa),
                          mysqli_real_escape_string($polaczenie, $wyb_przedmiot));

          if($polaczenie->query($sql))
            $_SESSION['edytowanie_przedmiotow'] = "Nazwa przedmiotu została zedytowana";
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
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Zobacz, Dodaj, Usuń, Edytuj PRZEDMIOT</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <a href="../wszyscy/dziennik.php" class="navbar-brand">BDG DZIENNIK</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#glowneMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="glowneMenu" class="collapse navbar-collapse">
        <ul class="navbar-nav  ml-auto">
          <?php
            if ( $_SESSION['uprawnienia'] == "a") {
              echo '<li class="nav-item"><a href="admin_klasy.php" class="nav-link">KLASY</a></li>';
              echo '<li class="nav-item"><a href="admin_sale.php" class="nav-link">SALE</a></li>';
              echo '<li class="nav-item"><a href="admin_przedmioty.php" class="nav-link">PRZEDMIOTY</a></li>';
              echo '<li class="nav-item"><a href="admin_osoby.php" class="nav-link">OSOBY</a></li>';
              echo '<li class="nav-item"><a href="admin_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            } else if ( $_SESSION['uprawnienia'] == "n") {
              echo '<li class="nav-item"><a href="../nauczyciel/wybierz_przydzial.php" class="nav-link">OCENY</a></li>';
              echo '<li class="nav-item"><a href="../nauczyciel/nauczyciel_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            } else if ( $_SESSION['uprawnienia'] == "u") {
              echo '<li class="nav-item"><a href="../uczen/uczen_oceny.php" class="nav-link">OCENY</a></li>';
              echo '<li class="nav-item"><a href="../uczen/uczen_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            }
          ?>
          <li class="nav-item">
            <div class="dropdown">
              <a href="#" class="nav-item btn btn-dark dropdown-toggle" role="button" id="dropdownProfil"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                PROFIL
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="../wszyscy/zmien_dane.php">ZMIEŃ DANE</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../wszyscy/zadania/wyloguj.php">WYLOGÓJ</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <section>
      <h2>ZOBACZ PRZEDMIOT</h2>
      <?php
        if ($_SESSION['ilosc_przedmiotow'] == 0) {
          echo '<p>ŻADEN PRZEDMIOT NIE ISTNIEJE W BAZIE</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th>NUMER</th>';
              echo '<th>ID</th>';
              echo '<th>NAZWA</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++) {
            echo '<tr>';
              echo '<td>'.$i.'</td>';
              echo '<td>'.$_SESSION['przedmiot'.$i]['id'].'</td>';
              echo '<td>'.$_SESSION['przedmiot'.$i]['nazwa'].'</td>';
            echo '</tr>';
          }

          echo '</tbody>';
          echo '</table>';
        }
      ?>
    </section>
    <section>
      <form method="post">
        <h3>DODAJ PRZEDMIOT</h3>
        <input type="text" placeholder="Nazwa" name="nazwa"/>
        <button type="submit">Dodaj</button>
        <div class="info">
          <?php
            if (isset($_SESSION['dodawanie_przedmiotow'])) {
              echo '<p>'.$_SESSION['dodawanie_przedmiotow'].'</p>';
              unset($_SESSION['dodawanie_przedmiotow']);
            }
          ?>
        </div>
      </form>
    </section>
    <section>
      <form method="post">
        <h3>EDYTUJ PRZEDMIOT</h3>
        <select name="wyb_przedmiot">
          <?php
            for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++)
              echo '<option value="'.$_SESSION['przedmiot'.$i]['nazwa'].'">'.$_SESSION['przedmiot'.$i]['nazwa'].'</option>';
          ?>
        </select>
        <input type="text" placeholder="Nazwa" name="nazwa"/>
        <button type="submit">Edytuj</button>
        <div class="info">
          <?php
            if (isset($_SESSION['edytowanie_przedmiotow'])) {
              echo '<p>'.$_SESSION['edytowanie_przedmiotow'].'</p>';
              unset($_SESSION['edytowanie_przedmiotow']);
            }
          ?>
        </div>
      </form>
    </section>
    <section>
      <form method="post">
        <h3>USUŃ PRZEDMIOT</h3>
        <select name="wyb_przedmiot">
          <?php
            for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++)
              echo '<option value="'.$_SESSION['przedmiot'.$i]['id'].'">'.$_SESSION['przedmiot'.$i]['nazwa'].'</option>';
          ?>
        </select>
        <button type="submit">Usuń</button>
        <div class="info">
          <?php
            if (isset($_SESSION['usuwanie_przedmiotow'])) {
              echo '<p>'.$_SESSION['usuwanie_przedmiotow'].'</p>';
              unset($_SESSION['usuwanie_przedmiotow']);
            }
          ?>
        </div>
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

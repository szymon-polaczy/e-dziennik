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
                <a class="dropdown-item disabled" href="#">Imie: <span class="wartosc"><?php echo $_SESSION['imie']; ?></span></a>
                <a class="dropdown-item disabled" href="#">Nazwisko: <span class="wartosc"><?php echo $_SESSION['nazwisko']; ?></span></a>
                <a class="dropdown-item disabled" href="#">Email: <span class="wartosc"><?php echo $_SESSION['email']; ?></span></a>
                <?php
                  if ($_SESSION['uprawnienia'] == "n")
                    echo '<a class="dropdown-item disabled" href="#">Sala: <span class="wartosc">'.$_SESSION['sala_nazwa'].'</span></a>';
                  else if ($_SESSION['uprawnienia'] == "u")
                    echo '<a class="dropdown-item disabled" href="#">Klasa: <span class="wartosc">'.$_SESSION['klasa_nazwa'].'</span></a>';
                ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="zmien_dane.php">ZMIEŃ DANE</a>
                <a class="dropdown-item" href="../wszyscy/zadania/wyloguj.php">WYLOGUJ</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <section>
      <div class="container p-0">
        <p>
          <button class="dodawanie-collapse-btn btn btn-dark" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Dodaj salę
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <form method="post" action="zadania/dodawanie_sal.php">
            <div class="form-group">
              <label for="nazwa_sali">Wpisz nazwę sali</label>
              <input class="form-control" id="nazwa_sali" type="text" placeholder="Nazwa" name="nazwa"/>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['dodawanie_sal'])) {
                  echo '<p>'.$_SESSION['dodawanie_sal'].'</p>';
                  unset($_SESSION['dodawanie_sal']);
                }
              ?>

              <button class="btn btn-dark" type="submit">Dodaj</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <section>
      <h2>ZOBACZ SALE</h2>
      <?php
        if (isset($_SESSION['usuwanie_sal'])) {
          echo '<p class="form-text uzytk-blad">'.$_SESSION['usuwanie_sal'].'</p>';
          unset($_SESSION['usuwanie_sal']);
        }
        if (isset($_SESSION['edytowanie_sal'])) {
          echo '<p class="form-text uzytk-blad">'.$_SESSION['edytowanie_sal'].'</p>';
          unset($_SESSION['edytowanie_sal']);
        }

        if ($_SESSION['ilosc_sal'] == 0) {
          echo '<p class="form-text uzytk-blad">ŻADNA SALA NIE ISTNIEJE W BAZIE</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th class="tabela-liczby">#</th>';
              echo '<th class="tabela-tekst">NAZWA</th>';
              echo '<th class="tabela-zadania">OPCJE</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
            echo '<tr>';
              echo '<td class="tabela-liczby">'.$i.'</td>';
              echo '<td class="tabela-tekst">'.$_SESSION['sala'.$i]['nazwa'].'</td>';
              echo '<td class="tabela-zadania">';
                echo '<a href="edytowanie_sal.php?wyb_sala='.$_SESSION['sala'.$i]['id'].'">Edytuj</a>';
                echo '<span>|</span>';
                echo '<a onclick="javascript:(confirm(\'Czy jesteś tego pewny?\')? window.location=\'zadania/usuwanie_sal.php?wyb_sala='.$_SESSION['sala'.$i]['id'].'\':\'\')" href="#">Usuń</a>';
              echo '</td>';
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

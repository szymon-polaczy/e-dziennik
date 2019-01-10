<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  //Wyciągam przydziały
  $sql = "SELECT przydzial.*, klasa.nazwa AS klasa_nazwa, przedmiot.nazwa AS przedmiot_nazwa, nauczyciel.id_osoba, osoba.imie, osoba.nazwisko
            FROM przydzial, klasa, przedmiot, nauczyciel, osoba
            WHERE przydzial.id_przedmiot=przedmiot.id AND przydzial.id_klasa=klasa.id
            AND przydzial.id_nauczyciel=nauczyciel.id_osoba AND nauczyciel.id_osoba=osoba.id";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_przydzialow'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
    $_SESSION['przydzial'.$i] = $rezultat[$i];

  //Wyciągam nauczycieli
  $sql = "SELECT nauczyciel.*, osoba.imie, osoba.nazwisko FROM nauczyciel, osoba
          WHERE nauczyciel.id_osoba=osoba.id";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_nauczycieli'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_nauczycieli']; $i++)
    $_SESSION['nauczyciel'.$i] = $rezultat[$i];

  //Wyciągam nauczycieli
  $sql = "SELECT przedmiot.* FROM przedmiot";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_przedmiotow'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++)
    $_SESSION['przedmiot'.$i] = $rezultat[$i];

  //Wyciągam nauczycieli
  $sql = "SELECT klasa.* FROM klasa";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_klas'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++)
    $_SESSION['klasa'.$i] = $rezultat[$i];
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
        <form method="post" action="zadania/dodawanie_przydzialow.php">
          <h2>DODAJ PRZYDZIAŁY</h2>
          <?php
            if ($_SESSION['ilosc_nauczycieli'] <= 0 || $_SESSION['ilosc_przedmiotow'] <= 0 || $_SESSION['ilosc_klas'] <= 0) {
              echo '<div class="przydzial-wiersz" style="color: #f33">NIE MA NAUCZYCIELI LUB PRZEDMIOTÓW LUB KLAS. DODAJ PIERW WSZYSTKIE ELEMENTY!</div>';
            } else {
              echo '<div class="form-group">';
                echo '<select name="wyb_nauczyciel" class="form-control">';

                  for ($i = 0; $i < $_SESSION['ilosc_nauczycieli']; $i++)
                    echo '<option value="'.$_SESSION['nauczyciel'.$i]['id_osoba'].'">Nauczyciel '.$_SESSION['nauczyciel'.$i]['imie'].' '.$_SESSION['nauczyciel'.$i]['nazwisko'].'</option>';

                echo '</select>';
              echo '</div>';

              echo '<div class="form-group">';
                echo '<select name="wyb_przedmiot" class="form-control">';

                  for ($i = 0; $i < $_SESSION['ilosc_przedmiotow']; $i++)
                    echo '<option value="'.$_SESSION['przedmiot'.$i]['id'].'">Przedmiot '.$_SESSION['przedmiot'.$i]['nazwa'].'</option>';

                echo '</select>';
              echo '</div>';

              echo '<div class="form-group">';
                echo '<select name="wyb_klasa" class="form-control">';

                  for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++)
                    echo '<option value="'.$_SESSION['klasa'.$i]['id'].'">Klasa '.$_SESSION['klasa'.$i]['nazwa'].' | '.$_SESSION['klasa'.$i]['opis'].'</option>';

                echo '</select>';
              echo '</div>';

              echo '<div class="form-group form-inf">';
                echo '<button type="submit" class="btn btn-dark">DODAJ</button>';

                if (isset($_SESSION['dodawanie_przydzialow'])) {
                  echo '<p>'.$_SESSION['dodawanie_przydzialow'].'</p>';
                  unset($_SESSION['dodawanie_przydzialow']);
                }

              echo '</div>';
            }
          ?>
        </form>
      </div>
    </section>
    <section>
      <h2>ZOBACZ PRZYDZIAŁY</h2>
      <?php
        if (isset($_SESSION['edytowanie_przydzialow'])) {
          echo '<p>'.$_SESSION['edytowanie_przydzialow'].'</p>';
          unset($_SESSION['edytowanie_przydzialow']);
        }

        if (isset($_SESSION['usuwanie_przydzialow'])) {
          echo '<p>'.$_SESSION['usuwanie_przydzialow'].'</p>';
          unset($_SESSION['usuwanie_przydzialow']);
        }

        if ($_SESSION['ilosc_przydzialow'] <= 0) {
          echo '<p>NIE MA ŻADNCH PRZYDZIAŁÓW, NAJPIERW DODAJ JAKIEŚ</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th class="tabela-liczby">#</th>';
              echo '<th class="tabela-tekst">IMIE NAUCZYCIELA</th>';
              echo '<th class="tabela-tekst">NAZWISKO NAUCZYCIELA</th>';
              echo '<th class="tabela-tekst">NAZWA PRZEDMIOTU</th>';
              echo '<th class="tabela-tekst">NAZWA KLASY</th>';
              echo '<th class="tabela-zadania">EDYTUJ</th>';
              echo '<th class="tabela-zadania">USUWANIE</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++) {
            echo '<tr>';
              echo '<td class="tabela-liczby">'.$i.'</td>';
              echo '<td class="tabela-tekst">'.$_SESSION['przydzial'.$i]['imie'].'</td>';
              echo '<td class="tabela-tekst">'.$_SESSION['przydzial'.$i]['nazwisko'].'</td>';
              echo '<td class="tabela-tekst">'.$_SESSION['przydzial'.$i]['przedmiot_nazwa'].'</td>';
              echo '<td class="tabela-tekst">'.$_SESSION['przydzial'.$i]['klasa_nazwa'].'</td>';
              echo '<td><a href="edytowanie_przydzialow.php?wyb_przydzial='.$_SESSION['przydzial'.$i]['id'].'">Edytuj</a></td>';
              echo '<td class="tabela-zadania"><a href="zadania/usuwanie_przydzialow.php?wyb_przydzial='.$_SESSION['przydzial'.$i]['id'].'">Usuń</a></td>';
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

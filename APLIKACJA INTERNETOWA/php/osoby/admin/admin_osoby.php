<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (!isset($_SESSION['zalogowany'])) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  //------------------------------------------------WYŚWIETLNIE OSOB-----------------------------------------------//
  $sql = "SELECT * FROM osoba";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_osob'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_osob']; $i++)
    $_SESSION['osoba'.$i] = $rezultat[$i];

  //WYCIĄGANIE DODATKOWYCH INFORMACJI
  for ($i = 0; $i < $_SESSION['ilosc_osob']; $i++) {
    //NAUCZYCIEL
    if ($_SESSION['osoba'.$i]['uprawnienia'] == "n") {
      $id_osoba = $_SESSION['osoba'.$i]['id'];
      $sql = "SELECT nazwa FROM osoba, nauczyciel, sala WHERE osoba.id='$id_osoba' AND nauczyciel.id_osoba=osoba.id AND nauczyciel.id_sala=sala.id";

      $rezultat = $pdo->sql_value($sql);

      $_SESSION['osoba'.$i]['sala_nazwa'] = $rezultat;
    }

    //UCZEN
    if ($_SESSION['osoba'.$i]['uprawnienia'] == "u") {
      $id_osoba = $_SESSION['osoba'.$i]['id'];
      $sql = "SELECT data_urodzenia, nazwa, opis FROM osoba, uczen, klasa WHERE osoba.id='$id_osoba' AND uczen.id_osoba=osoba.id AND klasa.id=uczen.id_klasa";

      $rezultat = $pdo->sql_record($sql);

      $_SESSION['osoba'.$i]['data_urodzenia'] = $rezultat['data_urodzenia'];
      $_SESSION['osoba'.$i]['klasa_nazwa'] = $rezultat['nazwa'];
      $_SESSION['osoba'.$i]['klasa_opis'] = $rezultat['opis'];
    }
  }

  //------------------------------------------------WYCIĄGANIE KLAS-----------------------------------------------//
  $sql = "SELECT * FROM klasa";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_klas'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++)
    $_SESSION['klasa'.$i] = $rezultat[$i];

  //------------------------------------------------WYCIĄGANIE SAL-----------------------------------------------//
  $sql = "SELECT * FROM sala";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_sal'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++)
    $_SESSION['sala'.$i] = $rezultat[$i];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - ZOBACZ, DODAJ, USUŃ, EDYTUJ OSOBY</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js" type="text/javascript"></script>
  <script src="../../../js/script.js" type="text/javascript"></script>
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
            Dodaj osobę
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <form action="zadania/dodawanie_osob.php" method="post">
            <div class="form-group">
              <label for="dodajImie">Wpisz Imię</label>
              <input id="dodajImie" class="form-control" type="text" placeholder="Imię" name="imie"/>
            </div>
            <div class="form-group">
              <label for="dodajImie">Wpisz Nazwisko</label>
              <input id="dodajImie" class="form-control" type="text" placeholder="Nazwisko" name="nazwisko"/>
            </div>
            <div class="form-group">
              <label for="dodajImie">Wpisz Email</label>
              <input id="dodajImie" class="form-control" type="email" placeholder="Email" name="email"/>
            </div>
            <div class="form-group">
              <label for="dodajImie">Wpisz Hasło</label>
              <input id="dodajImie" class="form-control" type="password" placeholder="Hasło" name="haslo"/>
            </div>
            <div class="form-group">
              <label for="nadajUprawnienia">Nadaj Uprawnienia</label>
              <select class="form-control" id="nadajUprawnienia"  name="uprawnienia" onchange="pokazUzupelnienie()">
                <option value="a">Administrator</option>
                <option value="n">Nauczyciel</option>
                <option value="u">Uczeń</option>
              </select>
            </div>
            <div class="niewidoczne" id="nauczyciel-uzu">
              <?php
                if ($_SESSION['ilosc_sal'] == 0) {
                  echo '<span style="color: red;">Nie ma żadnej sali z którą można połączyć nauczyciela. Dodaj pierw klasy!</span>';
                } else {
                  echo '<div class="form-group">';
                    echo '<label for="wybierzSale">Wybierz Salę</label>';
                    echo '<select class="form-control" id="wybierzSale" name="wyb_sala">';

                    for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++)
                      echo '<option value="'.$_SESSION['sala'.$i]['id'].'">'.$_SESSION['sala'.$i]['nazwa'].'</option>';

                    echo '</select>';
                  echo '</div>';
                }
              ?>
            </div>
            <div class="niewidoczne" id="uczen-uzu">
              <?php
                if ($_SESSION['ilosc_klas'] == 0) {
                  echo '<span style="color: red;">Nie ma żadnej klasy z którą można połączyć nauczyciela. Dodaj pierw klasy!</span>';
                } else {
                  echo '<div class="form-group">';
                    echo '<label for="dataUrodzenia">Wybierz Datę Urodzenia</label>';
                    echo '<input id="dataUrodzenia" class="form-control" type="date" name="data_urodzenia"/>';
                  echo '</div>';

                  echo '<div class="form-group">';
                    echo '<label for="wybierzKlase">Wybierz Klasę</label>';
                    echo '<select class="form-control" id="wybierzKlase" name="wyb_klasa">';

                    for ($i = 0; $i < $_SESSION['ilosc_klas']; $i++)
                      echo '<option value="'.$_SESSION['klasa'.$i]['id'].'">'.$_SESSION['klasa'.$i]['nazwa'].'</option>';

                    echo '</select>';
                  echo '</div>';
                }
              ?>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['dodawanie_osob'])) {
                  echo '<small class="form-text uzytk-blad">'.$_SESSION['dodawanie_osob'].'</small>';
                  unset($_SESSION['dodawanie_osob']);
                }
                ?>

              <button class="btn btn-dark" type="submit">Dodaj</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <section>
      <h2>ZOBACZ OSOBY</h2>
      <?php
        //HEH to się nigdy nie wydarzy
        if ($_SESSION['ilosc_osob'] == 0) {
          echo '<p>Nie ma żadnych osób w bazie</p>';
        }

        //ZADANIA PHP
        if (isset($_SESSION['usuwanie_osob'])) {
          echo '<small   class="form-text uzytk-blad">'.$_SESSION['usuwanie_osob'].'</small>';
          unset($_SESSION['usuwanie_osob']);
        }

        if (isset($_SESSION['edytowanie_osob'])) {
          echo '<small   class="form-text uzytk-blad">'.$_SESSION['edytowanie_osob'].'</small>';
          unset($_SESSION['edytowanie_osob']);
        }

        //WYŚWIETLAM ADMINISTATORÓW
        echo '<table class="table">';
        echo '<thead class="thead-dark">';
          echo '<tr>';
            echo '<th class="tabela-liczby">#</th>';
            echo '<th class="tabela-tekst">IMIE</th>';
            echo '<th class="tabela-tekst">NAZWISKO</th>';
            echo '<th class="tabela-tekst">EMAIL</th>';
            echo '<th class="tabela-tekst">HASŁO</th>';
            echo '<th class="tabela-tekst">UPRAWNIENIA</th>';
            echo '<th class="tabela-zadania">EDYTOWANIE</th>';
            echo '<th class="tabela-zadania">USUWANIE</th>';
          echo '</tr>';
        echo '</thead>';

        echo '<tbody>';

        for ($i = 0; $i < $_SESSION['ilosc_osob']; $i++) {
          if ($_SESSION['osoba'.$i]['uprawnienia'] == "a") {
            echo '<tr>';
            echo '<td class="tabela-liczby">'.$i.'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['imie'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['nazwisko'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['email'].'</td>';
            echo '<td class="tabela-tekst">'.substr($_SESSION['osoba'.$i]['haslo'], 0, 4).'...'.'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['uprawnienia'].'</td>';
            echo '<td class="tabela-zadania"><a href="edytowanie_osob.php?wyb_osoba='.$_SESSION['osoba'.$i]['id'].'">Edytuj</a></td>';
            echo '<td class="td-task"><a onclick="javascript:(confirm(\'Czy jesteś tego pewny?\')? window.location=\'zadania/usuwanie_osob.php?wyb_osoba='.$_SESSION['osoba'.$i]['id'].'&numer_osoby='.$i.'\':\'\')" href="#">Usuń</a></td>';
            echo '</tr>';
          }
        }

        echo '</tbody>';
        echo '</table>';



        //WYŚWIETLAM NAUCZYCIELi
        echo '<table class="table">';
        echo '<thead class="thead-dark">';
          echo '<tr>';
            echo '<th class="tabela-liczby">#</th>';
            echo '<th class="tabela-tekst">IMIE</th>';
            echo '<th class="tabela-tekst">NAZWISKO</th>';
            echo '<th class="tabela-tekst">EMAIL</th>';
            echo '<th class="tabela-tekst">HASŁO</th>';
            echo '<th class="tabela-tekst">UPRAWNIENIA</th>';
            echo '<th class="tabela-tekst">NAZWA SALI</th>';
            echo '<th class="tabela-zadania">EDYTOWANIE</th>';
            echo '<th class="tabela-zadania">USUWANIE</th>';
          echo '</tr>';
        echo '</thead>';

        echo '<tbody>';

        for ($i = 0; $i < $_SESSION['ilosc_osob']; $i++) {
          if ($_SESSION['osoba'.$i]['uprawnienia'] == "n") {
            echo '<tr>';
            echo '<td class="tabela-liczby">'.$i.'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['imie'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['nazwisko'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['email'].'</td>';
            echo '<td class="tabela-tekst">'.substr($_SESSION['osoba'.$i]['haslo'], 0, 4).'...'.'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['uprawnienia'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['sala_nazwa'].'</td>';
            echo '<td class="tabela-zadania"><a href="edytowanie_osob.php?wyb_osoba='.$_SESSION['osoba'.$i]['id'].'">Edytuj</a></td>';
            echo '<td class="tabela-zadania"><a href="zadania/usuwanie_osob.php?wyb_osoba='.$_SESSION['osoba'.$i]['id'].'&numer_osoby='.$i.'">Usuń</a></td>';
            echo '</tr>';
          }
        }

        echo '</tbody>';
        echo '</table>';



        //UCZNIOWIE
        echo '<table class="table">';
        echo '<thead class="thead-dark">';
          echo '<tr>';
            echo '<th class="tabela-liczby">#</th>';
            echo '<th class="tabela-tekst">IMIE</th>';
            echo '<th class="tabela-tekst">NAZWISKO</th>';
            echo '<th class="tabela-tekst">EMAIL</th>';
            echo '<th class="tabela-tekst">HASŁO</th>';
            echo '<th class="tabela-tekst">UPRAWNIENIA</th>';
            echo '<th class="tabela-liczby">DATA URODZENIA</th>';
            echo '<th class="tabela-tekst">NAZWA KLASY</th>';
            echo '<th class="tabela-tekst">OPIS KLASY</th>';
            echo '<th class="tabela-zadania">EDYTOWANIE</th>';
            echo '<th class="tabela-zadania">USUWANIE</th>';
          echo '</tr>';
        echo '</thead>';

        echo '<tbody>';

        for ($i = 0; $i < $_SESSION['ilosc_osob']; $i++) {
          if ($_SESSION['osoba'.$i]['uprawnienia'] == "u") {
            echo '<tr>';
            echo '<td class="tabela-liczby">'.$i.'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['imie'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['nazwisko'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['email'].'</td>';
            echo '<td class="tabela-tekst">'.substr($_SESSION['osoba'.$i]['haslo'], 0, 4).'...'.'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['uprawnienia'].'</td>';
            echo '<td class="tabela-liczby">'.$_SESSION['osoba'.$i]['data_urodzenia'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['klasa_nazwa'].'</td>';
            echo '<td class="tabela-tekst">'.$_SESSION['osoba'.$i]['klasa_opis'].'</td>';
            echo '<td class="tabela-zadania"><a href="edytowanie_osob.php?wyb_osoba='.$_SESSION['osoba'.$i]['id'].'">Edytuj</a></td>';
            echo '<td class="tabela-zadania"><a href="zadania/usuwanie_osob.php?wyb_osoba='.$_SESSION['osoba'.$i]['id'].'&numer_osoby='.$i.'">Usuń</a></td>';
            echo '</tr>';
          }
        }

        echo '</tbody>';
        echo '</table>';
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

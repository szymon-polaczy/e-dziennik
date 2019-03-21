<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
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
  $_SESSION['przydzialy'] = $rezultat;


  //Wyciągam nauczycieli
  $sql = "SELECT nauczyciel.*, osoba.imie, osoba.nazwisko FROM nauczyciel, osoba WHERE nauczyciel.id_osoba=osoba.id";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['nauczyciele'] = $rezultat;


  //Wyciągam przedmioty
  $sql = "SELECT przedmiot.* FROM przedmiot";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['przedmioty'] = $rezultat;


  //Wyciągam klasy
  $sql = "SELECT klasa.* FROM klasa";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['klasy'] = $rezultat;
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
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <div class="container p-0">
        <p>
          <button class="dodawanie-collapse-btn btn btn-dark" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Dodaj przydziały
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <form method="post" action="zadania/dodawanie_przydzialow.php">
            <?php
              if (count($_SESSION['nauczyciele']) == 0 || count($_SESSION['przedmioty']) <= 0 || count($_SESSION['klasy']) <= 0) {
                echo '<div class="przydzial-wiersz" style="color: #f33">NIE MA NAUCZYCIELI LUB PRZEDMIOTÓW LUB KLAS. DODAJ PIERW WSZYSTKIE ELEMENTY!</div>';
              } else {
                echo '<div class="form-group">';
                  echo '<label for="wyb_nauczyciela">Wybierz Nauczyciela</label>';
                  echo '<select name="wyb_nauczyciel" id="wyb_nauczyciela" class="form-control" required>';
                    echo '<option></option>';

                    foreach ($_SESSION['nauczyciele'] as $nauczyciel)
                      echo '<option value="'.$nauczyciel['id_osoba'].'">Nauczyciel '.$nauczyciel['imie'].' '.$nauczyciel['nazwisko'].'</option>';

                  echo '</select>';
                echo '</div>';

                echo '<div class="form-group">';
                  echo '<label for="wyb_przedmiot">Wybierz Przedmiot</label>';
                  echo '<select name="wyb_przedmiot" id="wyb_przedmiot" class="form-control" required>';
                    echo '<option></option>';

                    foreach ($_SESSION['przedmioty'] as $przedmiot)
                      echo '<option value="'.$przedmiot['id'].'">Przedmiot '.$przedmiot['nazwa'].'</option>';

                  echo '</select>';
                echo '</div>';

                echo '<div class="form-group">';
                echo '<label for="wyb_klase">Wybierz Klasę</label>';
                  echo '<select name="wyb_klasa" id="wyb_klase" class="form-control" required>';
                    echo '<option></option>';

                    foreach ($_SESSION['klasy'] as $klasa)
                      echo '<option value="'.$klasa['id'].'">Klasa '.$klasa['nazwa'].' | '.$klasa['opis'].'</option>';

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
      </div>
    </section>
    <section>
      <h2>ZOBACZ PRZYDZIAŁY</h2>
      <?php
        if (isset($_SESSION['edytowanie_przydzialow'])) {
          echo '<small class="form-text uzytk-blad">'.$_SESSION['edytowanie_przydzialow'].'</small>';
          unset($_SESSION['edytowanie_przydzialow']);
        }

        if (isset($_SESSION['usuwanie_przydzialow'])) {
          echo '<small class="form-text uzytk-blad">'.$_SESSION['usuwanie_przydzialow'].'</small>';
          unset($_SESSION['usuwanie_przydzialow']);
        }

        if (count($_SESSION['przydzialy']) <= 0) {
          echo '<p class="form-text uzytk-blad">NIE MA ŻADNCH PRZYDZIAŁÓW, NAJPIERW DODAJ JAKIEŚ</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th class="tabela-tekst">IMIE NAUCZYCIELA</th>';
              echo '<th class="tabela-tekst">NAZWISKO NAUCZYCIELA</th>';
              echo '<th class="tabela-tekst">NAZWA PRZEDMIOTU</th>';
              echo '<th class="tabela-tekst">NAZWA KLASY</th>';
              echo '<th class="tabela-zadania">OPCJE</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          foreach ($_SESSION['przydzialy'] as $przydzial) {
            echo '<tr>';
              echo '<td class="tabela-tekst">'.$przydzial['imie'].'</td>';
              echo '<td class="tabela-tekst">'.$przydzial['nazwisko'].'</td>';
              echo '<td class="tabela-tekst">'.$przydzial['przedmiot_nazwa'].'</td>';
              echo '<td class="tabela-tekst">'.$przydzial['klasa_nazwa'].'</td>';
              echo '<td class="tabela-zadania">';
                echo '<a href="edytowanie_przydzialow.php?wyb_przydzial='.$przydzial['id'].'">Edytuj</a>';
                echo '<span>|</span>';
                echo '<a onclick="javascript:(confirm(\'Czy jesteś tego pewny?\')? window.location=\'zadania/usuwanie_przydzialow.php?wyb_przydzial='.$przydzial['id'].'\':\'\')" href="#">Usuń</a>';
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

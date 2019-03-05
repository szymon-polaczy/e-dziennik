<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_GET['wyb_przydzial'])) {
    header('Location: admin_przydzialy.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  //Wyciągam osoby
  $sql = "SELECT * FROM osoba, nauczyciel WHERE uprawnienia='n' AND osoba.id=nauczyciel.id_osoba";

  $rezultat = $pdo->sql_table($sql);

  $ilosc_osob = count($rezultat);

  for ($i = 0; $i < $ilosc_osob; $i++)
    $_SESSION['osoba'.$i] = $rezultat[$i];

  //Wyciągam przedmioty
  $sql = "SELECT * FROM przedmiot";

  $rezultat = $pdo->sql_table($sql);

  $ilosc_przedmiotow = count($rezultat);

  for ($i = 0; $i < $ilosc_przedmiotow; $i++)
    $_SESSION['przedmiot'.$i] = $rezultat[$i];

  //Wyciągam klasy
  $sql = "SELECT * FROM klasa";

  $rezultat = $pdo->sql_table($sql);

  $ilosc_klas = count($rezultat);

  for ($i = 0; $i < $ilosc_klas; $i++)
    $_SESSION['klasa'.$i] = $rezultat[$i];

  //Wyciąganie edytowanego przydziału
  $wyb_przydzial = $_GET['wyb_przydzial'];
  $sql = "SELECT * FROM przydzial WHERE id='$wyb_przydzial'";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['edytowany_id'] = $rezultat[0]['id'];
  $_SESSION['edytowany_id_nauczyciel'] = $rezultat[0]['id_nauczyciel'];
  $_SESSION['edytowany_id_przedmiot'] = $rezultat[0]['id_przedmiot'];
  $_SESSION['edytowany_id_klasa'] = $rezultat[0]['id_klasa'];
?>
<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Edytuj Przydział</title>
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
    <div class="container p-0">
      <form method="post" action="zadania/edytowanie_przydzialow.php">
        <h2>Edytuj Przydział</h2>
        <div class="form-group">
          <label for="wybor_nauczyciela">Wybierz nauczyciela</label>
          <select name="wyb_nauczyciel" class="form-control" id="wybor_nauczyciela">
            <?php
              for ($i = 0; $i < $ilosc_osob; $i++)
                echo '<option '.($_SESSION['osoba'.$i]['id'] == $_SESSION['edytowany_id_nauczyciel']? 'selected' : '').' value="'.$_SESSION['osoba'.$i]['id_osoba'].'">Nauczyciel '.$_SESSION['osoba'.$i]['imie'].' '.$_SESSION['osoba'.$i]['nazwisko'].'</option>';
             ?>
          </select>
        </div>
        <div class="form-group">
          <label for="wybor_przedmiotu">Wybierz przedmiot</label>
          <select name="wyb_przedmiot" class="form-control" id="wybor_przedmiotu">
            <?php
              for ($i = 0; $i < $ilosc_przedmiotow; $i++)
                echo '<option '.($_SESSION['przedmiot'.$i]['id'] == $_SESSION['edytowany_id_przedmiot']? 'selected' : '').' value="'.$_SESSION['przedmiot'.$i]['id'].'">Przedmiot '.$_SESSION['przedmiot'.$i]['nazwa'].'</option>';
             ?>
          </select>
        </div>
        <div class="form-group">
          <label for="wybor_klasy">Wybierz klasę</label>
          <select name="wyb_klasa" class="form-control" id="wybor_klasy">
            <?php
              for ($i = 0; $i < $ilosc_klas; $i++)
                echo '<option '.($_SESSION['klasa'.$i]['id'] == $_SESSION['edytowany_id_klasa']? 'selected' : '').' value="'.$_SESSION['klasa'.$i]['id'].'">Klasa '.$_SESSION['klasa'.$i]['nazwa'].' | '.$_SESSION['klasa'.$i]['opis'].'</option>';
             ?>
          </select>
        </div>
        <div class="form-group form-inf">
        <button type="submit" class="btn btn-dark">Zmień</button>
        </div>
      </form>
    </div>

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

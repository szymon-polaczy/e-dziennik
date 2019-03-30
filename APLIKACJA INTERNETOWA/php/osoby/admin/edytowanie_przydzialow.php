<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if(!isset($_GET['wyb_przydzial'])) {
    header('Location: admin_przydzialy.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  //Wyciągam osoby
  $sql = "SELECT * FROM osoba, nauczyciel WHERE uprawnienia='n' AND osoba.id=nauczyciel.id_osoba";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['osoba'] = $rezultat;

  //Wyciągam przedmioty
  $sql = "SELECT * FROM przedmiot";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['przedmiot'] = $rezultat;

  //Wyciągam klasy
  $sql = "SELECT * FROM klasa";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['klasa'] = $rezultat;

  //Wyciąganie edytowanego przydziału
  $przydzial_id = $_GET['wyb_przydzial'];
  $sql = "SELECT * FROM przydzial WHERE id='$przydzial_id'";
  $edi = $pdo->sql_record($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj przydział"; include("../../../html-templates/inside-head.php"); ?>
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
          <select name="wyb_nauczyciel" class="form-control" id="wybor_nauczyciela" required>
            <?php
              foreach ($_SESSION['osoba'] as $osoba)
                echo '<option '.($osoba['id'] == $edi['id_nauczyciel']? 'selected' : '').' value="'.$osoba['id_osoba'].'">Nauczyciel '.$osoba['imie'].' '.$osoba['nazwisko'].'</option>';
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="wybor_przedmiotu">Wybierz przedmiot</label>
          <select name="wyb_przedmiot" class="form-control" id="wybor_przedmiotu" required>
            <?php
              foreach ($_SESSION['przedmiot'] as $przedmiot)
                echo '<option '.($przedmiot['id'] == $edi['id_przedmiot']? 'selected' : '').' value="'.$przedmiot['id'].'">Przedmiot '.$przedmiot['nazwa'].'</option>';
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="wybor_klasy">Wybierz klasę</label>
          <select name="wyb_klasa" class="form-control" id="wybor_klasy" required>
            <?php
              foreach ($_SESSION['klasa'] as $klasa)
                echo '<option '.($klasa['id'] == $edi['id_klasa']? 'selected' : '').' value="'.$klasa['id'].'">Klasa '.$klasa['nazwa'].' | '.$klasa['opis'].'</option>';
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

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

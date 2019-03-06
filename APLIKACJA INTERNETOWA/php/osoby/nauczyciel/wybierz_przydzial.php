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

  //wyciągam przydziały
  $moje_id = $_SESSION['id'];
  $sql = "SELECT przydzial.*, osoba.imie, osoba.nazwisko, przedmiot.nazwa AS przedmiot_nazwa, klasa.nazwa AS klasa_nazwa
          FROM przydzial, nauczyciel, osoba, przedmiot, klasa
          WHERE przydzial.id_nauczyciel='$moje_id'
          AND przydzial.id_nauczyciel=nauczyciel.id_osoba
          AND nauczyciel.id_osoba=osoba.id
          AND przydzial.id_klasa=klasa.id
          AND przydzial.id_przedmiot=przedmiot.id";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_przydzialow'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
    $_SESSION['przydzial'.$i] = $rezultat[$i];
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Wybierz przydział</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <h2>WYBIERZ PRZYDZIAŁ</h2>
    <section>
      <div class="container p-0">
        <form action="nauczyciel_oceny.php" method="get">
          <?php
            if ($_SESSION['ilosc_przydzialow'] == 0) {
              echo 'Nie ma żadnych przydziałów, musisz jakieś utworzyć, aby dodać do nich oceny';
            } else {
              echo '<div class="form-group">';
                echo '<label for="wyb_przydzial">Wybierz Przydział</label>';
                echo '<select name="wyb_przydzial" id="wyb_przydzial" class="form-control">';
                  echo '<option></option>';

                for ($i = 0; $i < $_SESSION['ilosc_przydzialow']; $i++)
                  echo '<option value="'.$_SESSION['przydzial'.$i]['id'].'">'.$_SESSION['przydzial'.$i]['imie']
                  .' '.$_SESSION['przydzial'.$i]['nazwisko']
                  .' | '.$_SESSION['przydzial'.$i]['przedmiot_nazwa']
                  .' | '.$_SESSION['przydzial'.$i]['klasa_nazwa'].'</option>';

                echo '</select>';
              echo '</div>';

              echo '<div class="form-group form-inf">';
                echo '<button type="submit" class="btn btn-dark">Wybierz</button>';
              echo '</div>';
            }
          ?>
        </form>
      </div>
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

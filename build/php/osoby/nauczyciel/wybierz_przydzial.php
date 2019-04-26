<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'n')) {
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

  $_SESSION['przydzialy'] = $rezultat;
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Wybierz przydział"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <h2>Which Assigmnents</h2>
    <form action="nauczyciel_oceny.php" method="get">
      <?php
        if (count($_SESSION['przydzialy']) == 0) {
          echo 'There are no assignments for you';
        } else {
          echo '<label for="wyb_przydzial">Which Assignments</label>';
          echo '<select name="wyb_przydzial" id="wyb_przydzial" required>';
            echo '<option></option>';

            foreach ($_SESSION['przydzialy'] as $przydzial)
              echo '<option value="'.$przydzial['id'].'">'.$przydzial['imie']
              .' '.$przydzial['nazwisko']
              .' | '.$przydzial['przedmiot_nazwa']
              .' | '.$przydzial['klasa_nazwa'].'</option>';

          echo '</select>';
          echo '<button type="submit">Next</button>';
        }
      ?>
    </form>

    <a href="../wszyscy/dziennik.php"><button>Home Page</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

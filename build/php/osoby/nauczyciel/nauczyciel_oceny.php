<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'n')) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  if(!isset($_GET['wyb_przydzial'])) {
    header('Location: wybierz_przydzial.php');
    exit();
  }

  $_SESSION['wyb_przydzial'] = $_GET['wyb_przydzial'];
  $wyb_przydzial = $_SESSION['wyb_przydzial'];

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $adm = new Adm($pdo);

  //pobieranie uczniów do wyświetlenia w selecie przy dodawaniu ocen
  $sql = "SELECT osoba.imie, osoba.nazwisko, osoba.id FROM osoba, uczen, przydzial
                  WHERE osoba.uprawnienia='u' AND uczen.id_osoba=osoba.id
                  AND przydzial.id_klasa=uczen.id_klasa AND przydzial.id='$wyb_przydzial'";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['uczniowie'] = $rezultat;

  //pobieranie ocen do wyświetlania w tabelce
  $sql = "SELECT ocena.*, osoba.imie, osoba.nazwisko FROM ocena, uczen, osoba
          WHERE ocena.id_uczen=uczen.id_osoba AND uczen.id_osoba=osoba.id AND ocena.id_przydzial='$wyb_przydzial'";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['oceny'] = $rezultat;
?>

<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Grades"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <button class="show-form-btn"><i class="fas fa-plus"></i></button>
      <form class="dis-form" method="post" action="zadania/dodawanie_ocen.php">
        <?php
          if (count($_SESSION['uczniowie']) == 0) {
            echo '<p>There are no learners';
          } else {
            echo '<label for="wyb_ucznia">Learner</label>';
            echo '<select name="wyb_uczen" id="wyb_ucznia" required>';
              echo '<option></option>';

            foreach ($_SESSION['uczniowie'] as $uczen)
              echo '<option value="'.$uczen['id'].'">'.$uczen['imie'].' '.$uczen['nazwisko'].'</option>';

            echo '</select>';

            $oceny = ['6', '6-', '5+', '5', '5-', '4+', '4', '4-', '3+', '3', '3-', '2+', '2', '2-', '1+', '1', '0'];

            echo '<label for="wyb_wartosc">Grade</label>';
            echo '<select name="wyb_wartosc" id="wyb_wartosc" required>';
            echo '<option></option>';

            foreach ($oceny as $ocena)
              echo '<option value="'.$ocena.'">'.$ocena.'</option>';

            echo '</select>';
                    
            echo '<button type="submit">Add</button>';
            echo '<input type="hidden" name="wyb_przydzial" value="'.$_SESSION['wyb_przydzial'].'">';
          }
        ?>
      </form>
    </section>
    <section>
      <h2>See Grades</h2>
      <?php
        if (isset($_SESSION['dodawanie_ocen'])) {
          echo '<p style="color: red">'.$_SESSION['dodawanie_ocen'].'</p>';
          unset($_SESSION['dodawanie_ocen']);
        }

        if (isset($_SESSION['edytowanie_ocen'])) {
          echo '<p style="color: red">'.$_SESSION['edytowanie_ocen'].'</p>';
          unset($_SESSION['edytowanie_ocen']);
        }

        if (isset($_SESSION['usuwanie_ocen'])) {
          echo '<p style="color: red">'.$_SESSION['usuwanie_ocen'].'</p>';
          unset($_SESSION['usuwanie_ocen']);
        }

        echo '<h1>Nie działa showDataTable dla tego</h1>';

        if (count($_SESSION['oceny']) == 0) {
          echo '<p>Nie ma żadnych ocen do wyświetlania</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th class="tabela-tekst">Name</th>';
              echo '<th class="tabela-tekst">Surname</th>';
              echo '<th class="tabela-liczby">Time</th>';
              echo '<th class="tabela-liczby">Value</th>';
              echo '<th class="tabela-zadania">Options</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          foreach ($_SESSION['oceny'] as $ocena) {
            echo '<tr>';
              echo '<td class="tabela-tekst">'.$ocena['imie'].'</td>';
              echo '<td class="tabela-tekst">'.$ocena['nazwisko'].'</td>';
              echo '<td class="tabela-liczby">'.$ocena['data'].'</td>';
              echo '<td class="tabela-liczby">'.$ocena['wartosc'].'</td>';
              echo '<td class="tabela-zadania">';
                echo '<a href="edytowanie_ocen.php?wyb_ocena='.$ocena['id'].'&wyb_przydzial='.$_SESSION['wyb_przydzial'].'"><i class="fas fa-pen"></i></a>';
                echo '<span>|</span>';
                echo '<a onclick="javascript:(confirm(\'Czy jesteś tego pewny?\')? window.location=\'zadania/usuwanie_ocen.php?wyb_ocena='.$ocena['id'].'&wyb_przydzial='.$_SESSION['wyb_przydzial'].'\':\'\')" href="#"><i class="fas fa-trash-alt"></i></a>';
              echo '</td>';
            echo '</tr>';
          }

          echo '</tbody>';
          echo '</table>';
        }
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button>Home Page</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

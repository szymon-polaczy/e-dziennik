<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'u')) {
    header('Location: ../wszyscy/dziennik.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  //wyciąganie ocen do wyświetlania
  $moje_id = $_SESSION['id'];
  $sql = "SELECT osoba.imie, osoba.nazwisko, przedmiot.nazwa, ocena.*
                  FROM osoba, nauczyciel, przydzial, przedmiot, uczen, ocena
                  WHERE uczen.id_osoba='$moje_id' AND ocena.id_uczen=uczen.id_osoba
                  AND ocena.id_przydzial=przydzial.id AND przydzial.id_nauczyciel=nauczyciel.id_osoba
                  AND nauczyciel.id_osoba=osoba.id AND przydzial.id_przedmiot=przedmiot.id";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['ilosc_ocen'] = count($rezultat);

  for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++)
    $_SESSION['ocena'.$i] = $rezultat[$i];
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Zobacz Oceny</title>
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
    <section>
      <h2>TWOJE OCENY</h2>
      <?php
        if ($_SESSION['ilosc_ocen'] == 0) {
          echo '<p>Nie posiadasz żadnych ocen</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th class="tabela-liczby">#</th>';
              echo '<th class="tabela-tekst">IMIE NAUCZYCIELA</th>';
              echo '<th class="tabela-tekst">NAZWISKO NAUCZYCIELA</th>';
              echo '<th class="tabela-tekst">NAZWA PRZEDMIOTU</th>';
              echo '<th class="tabela-liczby">DATA</th>';
              echo '<th class="tabela-liczby">WARTOŚĆ</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          for ($i = 0; $i < $_SESSION['ilosc_ocen']; $i++) {
            echo '<tr>';
              echo '<td class="tabela-liczby">'.$i.'</td>';
              echo '<td class="tabela-tekst">'.$_SESSION['ocena'.$i]['imie'].'</td>';
              echo '<td class="tabela-tekst">'.$_SESSION['ocena'.$i]['nazwisko'].'</td>';
              echo '<td class="tabela-tekst">'.$_SESSION['ocena'.$i]['nazwa'].'</td>';
              echo '<td class="tabela-liczby">'.$_SESSION['ocena'.$i]['data'].'</td>';
              echo '<td class="tabela-liczby">'.$_SESSION['ocena'.$i]['wartosc'].'</td>';
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

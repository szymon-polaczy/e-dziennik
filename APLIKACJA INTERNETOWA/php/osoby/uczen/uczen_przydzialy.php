<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'u')) {
    header('Location: ../wszyscy/dziennik.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $adm = new Adm($pdo);

  //wyciąganie przydziałów do wyświetlania - powtórzenie jest nie widać tego na stronie ale na phpmyadmin
  $moje_id = $_SESSION['id'];
  $sql = "SELECT przydzial.id, przedmiot.nazwa AS `przedmiot nazwa`, osoba.imie, osoba.nazwisko, klasa.nazwa AS `klasa nazwa`, sala.nazwa AS `sala nazwa`
          FROM osoba, nauczyciel, przydzial, przedmiot, klasa, uczen, sala
          WHERE przydzial.id_nauczyciel=nauczyciel.id_osoba AND nauczyciel.id_osoba=osoba.id
          AND nauczyciel.id_sala=sala.id AND przydzial.id_przedmiot=przedmiot.id
          AND przydzial.id_klasa=klasa.id AND uczen.id_klasa=klasa.id
          AND uczen.id_osoba='$moje_id'";

  $rezultat = $pdo->sql_table($sql);

  $_SESSION['przydzialy'] = $rezultat;
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Przydziały"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">  
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <h2>ZOBACZ PRZYDZIAŁY</h2>
      <?php
        if (count($_SESSION['przydzialy']) > 0)
          $adm->showDataTable($_SESSION['przydzialy']);
        else
          echo '<p>Nie ma żadnych przydziałów</p>';
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

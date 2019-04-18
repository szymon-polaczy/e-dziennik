<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $adm = new Adm($pdo);

  $klasy = $adm->getAllFrom("klasa");
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Sale"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <button>Dodaj klasę</button>
      <form action="zadania/dodawanie_klas.php" method="post">
        <label for="dodanieNazwa">Wpisz Nazwę</label>
        <input id="dodanieNazwa" type="text" placeholder="Nazwa" name="nazwa" required/>
        <label for="dodanieOpis">Wpisz Opis</label>
        <input id="dodanieOpis" type="text" placeholder="Opis" name="opis" required/>
        <?php
          if (isset($_SESSION['dodawanie_klas'])) {
            echo '<small>'.$_SESSION['dodawanie_klas'].'</small>';
            unset($_SESSION['dodawanie_klas']);
          }
        ?>
        <button type="submit">Dodaj</button>
      </form>
    </section>
    <section>
      <h2>KLASY</h2>
      <?php
        if (isset($_SESSION['usuwanie_klas'])) {
          echo '<small>'.$_SESSION['usuwanie_klas'].'</small>';
          unset($_SESSION['usuwanie_klas']);
        }

        if (isset($_SESSION['edytowanie_klas'])) {
          echo '<small>'.$_SESSION['edytowanie_klas'].'</small>';
          unset($_SESSION['edytowanie_klas']);
        }

        if (count($klasy) > 0)
          $adm->showDataTable($klasy, true, 'edytowanie_klas.php?wyb_klasa', 'usuwanie_klas.php?wyb_klasa');
        else
          '<p>Nie ma żadnych klas</p>';
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

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
  <?php $title = "Klasy"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <button class="show-form-btn">Dodaj klasę</button>
      <form class="dis-form" action="zadania/dodawanie_klas.php" method="post">
        <label for="dodawanie_nazwy">Dodaj nazwę</label>
        <input id="dodawanie_nazwy" name="nazwa" type="text" placeholder="nazwa" required>
        <label for="dodawanie_opisu">Dodaj opis</label>
        <input id="dodawanie_opisu" name="opis" type="text" placeholder="opis" required>
        <button type="submit">Dodaj</button>
        <?php
          if (isset($_SESSION['dodawanie_klas'])) {
            echo '<small class="user_info">'.$_SESSION['dodawanie_klas'].'</small>';
            unset($_SESSION['dodawanie_klas']);
          }
        ?>
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

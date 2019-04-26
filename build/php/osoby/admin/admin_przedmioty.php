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

  $przedmioty = $adm->getAllFrom("przedmiot");
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Przedmioty"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <button class="show-form-btn"><i class="fas fa-plus"></i></button>
      <form class="dis-form" action="zadania/dodawanie_przedmiotow.php" method="post">
        <label for="przedmiot-nazwa">Wpisz nazwę przedmiotu</label>
        <input name="nazwa" id="przedmiot-nazwa" placeholder="Nazwa" type="text" required>
        <button type="submit">Dodaj</button>
      </form>
    </section>
    <section>
      <h2>ZOBACZ PRZEDMIOT</h2>
      <?php
        if (isset($_SESSION['dodawanie_przedmiotow'])) {
          echo '<p>'.$_SESSION['dodawanie_przedmiotow'].'</p>';
          unset($_SESSION['dodawanie_przedmiotow']);
        }

        if (isset($_SESSION['edytowanie_przedmiotow'])) {
          echo '<small>'.$_SESSION['edytowanie_przedmiotow'].'</small>';
          unset($_SESSION['edytowanie_przedmiotow']);
        }

        if (isset($_SESSION['usuwanie_przedmiotow'])) {
          echo '<small>'.$_SESSION['usuwanie_przedmiotow'].'</small>';
          unset($_SESSION['usuwanie_przedmiotow']);
        }

        if (count($przedmioty) > 1)
          $adm->showDataTable($przedmioty, true, 'edytowanie_przedmiotow.php?wyb_przedmiot', 'usuwanie_przedmiotow.php?wyb_przedmiot');
        else
          echo '<p>Nie ma żadnych przedmiotów</p>';
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

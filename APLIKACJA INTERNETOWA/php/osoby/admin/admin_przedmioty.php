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

  //------------------------------------------------WYCIĄGANIE PRZEDMIOTÓW DO OBEJRZENIA-----------------------------------------------//

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $adm = new Adm($pdo);

  $sql = "SELECT * FROM przedmiot";
  $rezultat = $pdo->sql_table($sql);
  $przedmioty = $rezultat;
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
      <div class="container p-0">
        <p>
          <button class="dodawanie-collapse-btn btn btn-dark" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Dodaj przedmiot
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <form method="post" action="zadania/dodawanie_przedmiotow.php">
            <div class="form-group">
              <div class="form-group">
                <label for="przedmiot-nazwa">Wpisz nazwę przedmiotu</label>
                <input name="nazwa" id="przedmiot-nazwa" placeholder="Nazwa" type="text" class="form-control" required>
              </div>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['dodawanie_przedmiotow'])) {
                  echo '<p>'.$_SESSION['dodawanie_przedmiotow'].'</p>';
                  unset($_SESSION['dodawanie_przedmiotow']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Dodaj</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <section>
      <h2>ZOBACZ PRZEDMIOT</h2>
      <?php
        if (isset($_SESSION['edytowanie_przedmiotow'])) {
          echo '<small class="form-text uzytk-blad">'.$_SESSION['edytowanie_przedmiotow'].'</small>';
          unset($_SESSION['edytowanie_przedmiotow']);
        }

        if (isset($_SESSION['usuwanie_przedmiotow'])) {
          echo '<small class="form-text uzytk-blad">'.$_SESSION['usuwanie_przedmiotow'].'</small>';
          unset($_SESSION['usuwanie_przedmiotow']);
        }

        if (count($przedmioty) > 1)
          $adm->showDataTable($przedmioty, true, 'edytowanie_przedmiotow.php?wyb_przedmiot', 'usuwanie_przedmiotow.php?wyb_przedmiot');
        else
          echo '<p>Nie ma żadnych przedmiotów</p>';
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

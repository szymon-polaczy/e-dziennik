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

  $sale = $adm->getAllFrom("sala");
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
      <div class="container p-0">
        <p>
          <button class="dodawanie-collapse-btn btn btn-dark" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Dodaj salę
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <form method="post" action="zadania/dodawanie_sal.php">
            <div class="form-group">
              <label for="nazwa_sali">Wpisz nazwę sali</label>
              <input class="form-control" id="nazwa_sali" type="text" placeholder="Nazwa" name="nazwa" required/>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['dodawanie_sal'])) {
                  echo '<p>'.$_SESSION['dodawanie_sal'].'</p>';
                  unset($_SESSION['dodawanie_sal']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Dodaj</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <section>
      <h2>ZOBACZ SALE</h2>
      <?php
        if (isset($_SESSION['usuwanie_sal'])) {
          echo '<p class="form-text uzytk-blad">'.$_SESSION['usuwanie_sal'].'</p>';
          unset($_SESSION['usuwanie_sal']);
        }
        if (isset($_SESSION['edytowanie_sal'])) {
          echo '<p class="form-text uzytk-blad">'.$_SESSION['edytowanie_sal'].'</p>';
          unset($_SESSION['edytowanie_sal']);
        }

        if (count($sale) > 0)
          $adm->showDataTable($sale, true, 'edytowanie_sal.php?wyb_sala', 'usuwanie_sal.php?wyb_sala');
        else
          echo '<p>Nie ma żadnych sal</p>';
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

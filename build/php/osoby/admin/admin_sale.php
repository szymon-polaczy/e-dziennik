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
  <?php $title = "Rooms"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <button class="show-form-btn"><i class="fas fa-plus"></i></button>
      <form class="dis-form" action="zadania/dodawanie_sal.php" method="post">
        <label for="nazwa_sali">Name</label>
        <input id="nazwa_sali" type="text" placeholder="name" name="nazwa" required/>
        <button type="submit">Add</button>
      </form>
    </section>
    <section>
      <h2>See Rooms</h2>
      <?php
        if (isset($_SESSION['dodawanie_sal'])) {
          echo '<p>'.$_SESSION['dodawanie_sal'].'</p>';
          unset($_SESSION['dodawanie_sal']);
        }

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
          echo '<p>There are no rooms</p>';
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button>Home Page</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

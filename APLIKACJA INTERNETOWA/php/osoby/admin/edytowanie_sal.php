<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if (!isset($_GET['wyb_sala'])) {
    header('Location: admin_przydzialy.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  //Wyciąganie wybranej klasy
  $wyb_sala = $_GET['wyb_sala'];
  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  $sql = "SELECT * FROM sala WHERE id='$wyb_sala'";

  $rezultat = $pdo->sql_record($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj salę"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <div class="container p-0">
        <div class="row">
          <div class="col-12">
            <form method="post" action="zadania/edytowanie_sal.php">
              <h2>EDYTUJ SALE</h2>
              <div class="form-group">
                <label for="nazwa_sali">Edytuj nazwę sali</label>
                <input class="form-control" id="nazwa_sali" type="text" value="<?php echo $rezultat['nazwa']; ?>" name="nazwa" required/>
              </div>
              <div class="form-group form-inf">
                <input type="hidden" name="wyb_sala" value="<?php echo $rezultat['id']; ?>">
                <button class="btn btn-dark" type="submit">Zmień</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <a href="../wszyscy/dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

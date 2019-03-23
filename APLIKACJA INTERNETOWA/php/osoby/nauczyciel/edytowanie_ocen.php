<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'n')) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  if(!isset($_GET['wyb_przydzial']) || !isset($_GET['wyb_ocena'])) {
    header('Location: wybierz_przydzial.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  //wyciągam wartośc wybranej oceny
  $wyb_ocena = $_GET['wyb_ocena'];
  $sql = "SELECT wartosc FROM ocena WHERE id='$wyb_ocena'";

  $rezultat = $pdo->sql_value($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj ocenę"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <div class="container p-0">
        <div class="row">
          <div class="col-12">
            <form method="post" action="zadania/edytowanie_ocen.php">
              <h2>ZMIEŃ OCENĘ</h2>
              <div class="form-group">
                <label for="wyb_ocene">Zmień wartość oceny</label>
                <select name="wyb_wartosc" class="form-control" id="wyb_ocene" required>
                  <?php
                    $oceny = ['6', '6-', '5+', '5', '5-', '4+', '4', '4-', '3+', '3', '3-', '2+', '2', '2-', '1+', '1', '0'];

                    foreach ($oceny as $oc) 
                      echo '<option '.($oc == $rezultat? 'selected' : '').' value="'.$oc.'">'.$oc.'</option>';
                  ?>
                </select>
              </div>
              <div class="form-group form-inf">
                <input type="hidden" name="wyb_przydzial" value="<?php echo $_GET['wyb_przydzial']; ?>">
                <input type="hidden" name="wyb_ocena" value="<?php echo $wyb_ocena; ?>">
                <button class="btn btn-dark" type="submit">Zmień</button>
              </div>
            </form>
          </div>
        </div>
      </div>
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

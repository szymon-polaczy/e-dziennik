<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);  

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if (!isset($_GET['wyb_klasa'])) {
    header('Location: admin_klasy.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  //Wyciąganie wybranej klasy
  $wyb_klasa = $_GET['wyb_klasa'];
  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  $sql = "SELECT * FROM klasa WHERE id='$wyb_klasa'";

  $rezultat = $pdo->sql_record($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj klasę"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <div class="container p-0">
      <div class="row">
        <div class="col-md-12">
          <form action="zadania/edytowanie_klas.php" method="post">
            <h2>EDYTUJ KLASĘ</h2>
            <div class="form-group">
              <label for="zmianaNazwy">Zmień Nazwę</label>
              <input id="zmianaNazwy" class="form-control" type="text" value="<?php echo $rezultat['nazwa']; ?>" name="nazwa" required/>
            </div>
            <div class="form-group">
              <label for="zmianaOpisu">Zmień Opis</label>
              <input id="zmianaOpisu" class="form-control" type="text" value="<?php echo $rezultat['opis']; ?>" name="opis" required/>
            </div>
            <div class="form-group form-inf">
              <input type="hidden" value="<?php echo $wyb_klasa; ?>" name="wyb_klasa"/>
              <button class="btn btn-dark" type="submit">Zmień</button>
            </div>
          </form>
        </div>
      </div>
    </div>

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

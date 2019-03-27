<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);  

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if (!isset($_GET['wyb_osoba'])) {
    header('Location: admin_osoby.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../user-adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  $user_adm = new User_Adm($pdo);

  $id_osoba = $_GET['wyb_osoba'];

  $edytowana = $user_adm->getUserById($id_osoba);

  //sprawdzenie czy jest co wyświetlać
  if (count($edytowana) === 0) {
    $_SESSION['edytowanie_osob'] = "Osoba o takim ID nie istnieje!";
    header('Location: ../admin_osoby.php');
    exit();
  }
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj osobę"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <div class="container p-0">
      <div class="row">
        <div class="col-md-12">
          <form action="zadania/edytowanie_osob.php" method="post">
            <h2>EDYTUJ OSOBĘ</h2>
            <div class="form-group">
              <label for="zmianaImienia">Edytuj Imię</label>
              <input id="zmianaImienia" class="form-control" type="text" value="<?php echo $edytowana['imie']; ?>" name="imie" required/>
            </div>
            <div class="form-group">
              <label for="zmianaNazwiska">Edytuj Nazwisko</label>
              <input id="zmianaNazwiska" class="form-control" type="text" value="<?php echo $edytowana['nazwisko']; ?>" name="nazwisko" required/>
            </div>
            <div class="form-group">
              <label for="zmianaEmailu">Edytuj Email</label>
              <input id="zmianaEmailu" class="form-control" type="email" value="<?php echo $edytowana['email']; ?>" name="email" required/>
            </div>
            <div class="form-group">
              <label for="zmianHasla">Edytuj Haslo</label>
              <input id="zmianHasla" class="form-control" type="password" value="<?php echo $edytowana['haslo']; ?>" name="haslo" required/>
            </div>
            <div class="form-group form-inf">
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

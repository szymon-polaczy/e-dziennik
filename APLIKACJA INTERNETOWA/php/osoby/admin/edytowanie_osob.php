<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  if (!isset($_GET['wyb_osoba'])) {
    header('Location: admin_osoby.php');
    exit();
  }

  //Wyciągam wszystkie wartości użytkownika
  if (isset($_GET['wyb_osoba'])) {
    $id_osoba = $_GET['wyb_osoba'];
    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    $sql = "SELECT * FROM osoba WHERE id='$id_osoba'";

    $rezultat = $pdo->sql_record($sql);

    $_SESSION['edytowana'] = $rezultat;
  }
?>
<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Edytuj Osobę</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="../../../css/style.css">
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
              <?php echo '<input id="zmianaImienia" class="form-control" type="text" value="'.$_SESSION['edytowana']['imie'].'" name="imie"/>'; ?>
            </div>
            <div class="form-group">
              <label for="zmianaNazwiska">Edytuj Nazwisko</label>
              <?php echo '<input id="zmianaNazwiska" class="form-control" type="text" value="'.$_SESSION['edytowana']['nazwisko'].'" name="nazwisko"/>'; ?>
            </div>
            <div class="form-group">
              <label for="zmianaEmailu">Edytuj Email</label>
              <?php echo '<input id="zmianaEmailu" class="form-control" type="email" value="'.$_SESSION['edytowana']['email'].'" name="email"/>'; ?>
            </div>
            <div class="form-group">
              <label for="zmianHasla">Edytuj Haslo</label>
              <?php echo '<input id="zmianHasla" class="form-control" type="password" value="'.$_SESSION['edytowana']['haslo'].'" name="haslo"/>'; ?>
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

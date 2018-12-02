<?php
  session_start();

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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Edytuj Klasę</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body class="index-body">
  <header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <a href="../wszyscy/dziennik.php" class="navbar-brand">BDG DZIENNIK</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#glowneMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="glowneMenu" class="collapse navbar-collapse">
        <ul class="navbar-nav  ml-auto">
          <?php
            if ( $_SESSION['uprawnienia'] == "a") {
              echo '<li class="nav-item"><a href="admin_klasy.php" class="nav-link">KLASY</a></li>';
              echo '<li class="nav-item"><a href="admin_sale.php" class="nav-link">SALE</a></li>';
              echo '<li class="nav-item"><a href="admin_przedmioty.php" class="nav-link">PRZEDMIOTY</a></li>';
              echo '<li class="nav-item"><a href="admin_osoby.php" class="nav-link">OSOBY</a></li>';
              echo '<li class="nav-item"><a href="admin_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            } else if ( $_SESSION['uprawnienia'] == "n") {
              echo '<li class="nav-item"><a href="../nauczyciel/wybierz_przydzial.php" class="nav-link">OCENY</a></li>';
              echo '<li class="nav-item"><a href="../nauczyciel/nauczyciel_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            } else if ( $_SESSION['uprawnienia'] == "u") {
              echo '<li class="nav-item"><a href="../uczen/uczen_oceny.php" class="nav-link">OCENY</a></li>';
              echo '<li class="nav-item"><a href="../uczen/uczen_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            }
          ?>
          <li class="nav-item">
            <div class="dropdown">
              <a href="#" class="nav-item btn btn-dark dropdown-toggle" role="button" id="dropdownProfil"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                PROFIL
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item disabled" href="#">Imie: <span class="wartosc"><?php echo $_SESSION['imie']; ?></span></a>
                <a class="dropdown-item disabled" href="#">Nazwisko: <span class="wartosc"><?php echo $_SESSION['nazwisko']; ?></span></a>
                <a class="dropdown-item disabled" href="#">Email: <span class="wartosc"><?php echo $_SESSION['email']; ?></span></a>
                <?php
                  if ($_SESSION['uprawnienia'] == "n")
                    echo '<a class="dropdown-item disabled" href="#">Sala: <span class="wartosc">'.$_SESSION['sala_nazwa'].'</span></a>';
                  else if ($_SESSION['uprawnienia'] == "u")
                    echo '<a class="dropdown-item disabled" href="#">Klasa: <span class="wartosc">'.$_SESSION['klasa_nazwa'].'</span></a>';
                ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="zmien_dane.php">ZMIEŃ DANE</a>
                <a class="dropdown-item" href="../wszyscy/zadania/wyloguj.php">WYLOGUJ</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <div class="container p-0">
      <div class="row">
        <div class="col-md-12">
          <form action="zadania/edytowanie_klas.php" method="post">
            <h2>EDYTUJ KLASĘ</h2>
            <div class="form-group">
              <label for="zmianaNazwy">Edytuj Nazwę</label>
              <?php echo '<input id="zmianaNazwy" class="form-control" type="text" value="'.$rezultat['nazwa'].'" name="nazwa"/>'; ?>
            </div>
            <div class="form-group">
              <label for="zmianaOpisu">Edytuj Opis</label>
              <?php echo '<input id="zmianaOpisu" class="form-control" type="text" value="'.$rezultat['opis'].'" name="opis"/>'; ?>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['edytowanie_klas'])) {
                  echo '<small  class="form-text uzytk-blad">'.$_SESSION['edytowanie_klas'].'</small>';
                  unset($_SESSION['edytowanie_klas']);
                }
              ?>
              <input type="hidden" value="'.$wyb_klasa.'" name="wyb_klasa"/>

              <button class="btn btn-dark" type="submit">Edytuj</button>
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

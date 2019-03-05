<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Edytuj Ocenę</title>
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
    <section>
      <div class="container p-0">
        <div class="row">
          <div class="col-12">
            <form method="post" action="zadania/edytowanie_ocen.php">
              <h2>EDYTUJ OCENĘ</h2>
              <div class="form-group">
                <label for="nazwa_sali">Edytuj wartość oceny</label>
                <?php
                  $oceny = ['6', '6-', '5+', '5', '5-', '4+', '4', '4-', '3+', '3', '3-', '2+', '2', '2-', '1+', '1', '0'];

                  echo '<div class="form-group">';
                    echo '<select name="wyb_wartosc" class="form-control">';

                    for ($i = 0; $i < count($oceny); $i++)
                      echo '<option '.($oceny[$i] == $rezultat? 'selected' : '').' value="'.$oceny[$i].'">'.$oceny[$i].'</option>';

                    echo '</select>';
                  echo '</div>';
                ?>
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

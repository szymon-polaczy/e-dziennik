<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: index.php');
    exit();
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Zmień dane</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <a href="dziennik.php" class="navbar-brand">BDG DZIENNIK</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#glowneMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="glowneMenu" class="collapse navbar-collapse">
        <ul class="navbar-nav  ml-auto">
          <?php
            if ( $_SESSION['uprawnienia'] == "a") {
              echo '<li class="nav-item"><a href="../admin/admin_klasy.php" class="nav-link">KLASY</a></li>';
              echo '<li class="nav-item"><a href="../admin/admin_sale.php" class="nav-link">SALE</a></li>';
              echo '<li class="nav-item"><a href="../admin/admin_przedmioty.php" class="nav-link">PRZEDMIOTY</a></li>';
              echo '<li class="nav-item"><a href="../admin/admin_osoby.php" class="nav-link">OSOBY</a></li>';
              echo '<li class="nav-item"><a href="../admin/admin_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            } else if ( $_SESSION['uprawnienia'] == "n") {
              echo '<li class="nav-item"><a href="../nauczyciel/wybierz_przydzial.php" class="nav-link">OCENY</a></li>';
              echo '<li class="nav-item"><a href="../nauczyciel/nauczyciel_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            } else if ( $_SESSION['uprawnienia'] == "u") {
              echo '<li class="nav-item"><a href="../uczen/uczen_oceny.php" class="nav-link">OCENY</a></li>';
              echo '<li class="nav-item"><a href="../uczen/uczen_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
            }
            echo '<div class="dropdown-divider"></div>';
            echo '<li class="nav-item"><a href="profil.php" class="nav-link">PROFIL</a></li>';
          ?>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <div class="container p-0">
      <div class="row">
        <div class="col-md-6">
          <form>
            <h2>Zmień Hasło</h2>
            <div class="form-group">
              <label for="zmianaHaslaStary">Wpisz Stare Hasło</label>
              <input id="zmianaHaslaStary" class="form-control" type="password" placeholder="Stare hasło" name="shaslo">
            </div>
            <div class="form-group">
              <label for="zmianaHaslaNowe">Wpisz Nowe Hasło</label>
              <input id="zmianaHaslaNowe" class="form-control" type="password" placeholder="Nowe hasło" name="nhaslo">
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['zmiana_hasla'])) {
                  echo '<small id="logowaniePomoc" class="form-text uzytk-blad">'.$_SESSION['zmiana_hasla'].'</small>';
                  unset($_SESSION['zmiana_hasla']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Zmień Hasło</button>
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <form action="zadania/zmiana_emailu.php" method="post">
            <h2>Zmień Email</h2>
            <div class="form-group">
              <label for="zmianaEmailuStary">Wpisz Stary Email</label>
              <input id="zmianaEmailuStary" class="form-control" type="email" placeholder="Stary email" name="semail">
            </div>
            <div class="form-group">
              <label for="zmianaEmailuNowy">Wpisz Nowy Email</label>
              <input id="zmianaEmailuNowy" class="form-control" type="email" placeholder="Nowy email" name="nemail">
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['zmiana_emailu'])) {
                  echo '<small id="logowaniePomoc" class="form-text uzytk-blad">'.$_SESSION['zmiana_emailu'].'</small>';
                  unset($_SESSION['zmiana_emailu']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Zmień Email</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <a href="dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <footer class="fixed-bottom bg-dark glowna-stopka">
    <h6>Autor: Szymon Polaczy</h6>
  </footer>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

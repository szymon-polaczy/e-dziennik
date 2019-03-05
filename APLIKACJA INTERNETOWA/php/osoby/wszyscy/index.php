<?php
  session_start();

  if(isset($_SESSION['zalogowany'])) {
    header('Location: dziennik.php');
    exit();
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Zaloguj Się</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Szymon Polaczy">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>

  <header>
    <nav class="navbar navbar-dark bg-dark">
      <a href="#" class="navbar-brand">BDG DZIENNIK</a>
      <span class="navbar-text">Twój następny e-dziennik.</span>
    </nav>
  </header>

  <main>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <form action="zadania/logowanie.php" method="post">
            <h2>Zaloguj Się</h2>
            <div class="form-group">
              <label for="logowanieEmail">Wpisz Email</label>
              <input id="logowanieEmail" class="form-control" type="email" placeholder="Email" name="email">
            </div>
            <div class="form-group">
              <label for="logowanieHaslo">Wpisz Hasło</label>
              <input id="logowanieHaslo" class="form-control" type="password" placeholder="Hasło" name="haslo">
            </div>
            <div class="form-group form-inf">
              <small  class="form-text text-muted">Nie udostępniamy nikomu twojego emailu oraz wszystkie twoje hasła są zaszyfrowane.</small>
              <?php
                if (isset($_SESSION['login_blad'])) {
                  echo '<small  class="form-text uzytk-blad">'.$_SESSION['login_blad'].'</small>';
                  unset($_SESSION['login_blad']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Zaloguj Się</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer class="fixed-bottom bg-dark glowna-stopka">
    <h6>Autor: Szymon Polaczy</h6>
  </footer>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

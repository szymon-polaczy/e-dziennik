<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(isset($_SESSION['zalogowany'])) {
    header('Location: dziennik.php');
    exit();
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Zaloguj się"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <header>
    <nav class="navbar navbar-dark bg-dark">
      <a href="#" class="navbar-brand">E-DZIENNIK</a>
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
              <input id="logowanieEmail" class="form-control" type="email" placeholder="Email" name="email" required>
            </div>
            <div class="form-group">
              <label for="logowanieHaslo">Wpisz Hasło</label>
              <input id="logowanieHaslo" class="form-control" type="password" placeholder="Hasło" name="haslo" required>
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

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

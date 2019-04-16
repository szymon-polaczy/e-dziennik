<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (isset($_SESSION['zalogowany'])) {
    header('Location: dziennik.php');
    exit();
  }
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Zaloguj się";
  include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <header class="navigation-header">
    <h2 class="logo"><a href="../wszyscy/index.php">e-dziennik</a></h2>
    <p>Twój następny e-dziennik</p>
  </header>

  <main>
    <form action="zadania/logowanie.php" method="post">
      <h2>Zaloguj Się</h2>
      <label for="logowanieEmail">Wpisz Email</label>
      <input id="logowanieEmail" type="email" placeholder="Email" name="email" required>
      <label for="logowanieHaslo">Wpisz Hasło</label>
      <input id="logowanieHaslo" type="password" placeholder="Hasło" name="haslo" required>
      <small class="form-text text-muted">Nie udostępniamy nikomu twojego emailu a wszystkie twoje hasła są zaszyfrowane.</small>
      <?php
        if (isset($_SESSION['login_blad'])) {
          echo '<small>'.$_SESSION['login_blad'].'</small>';
          unset($_SESSION['login_blad']);
        }
      ?>
      <button type="submit">Zaloguj Się</button>
    </form>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>
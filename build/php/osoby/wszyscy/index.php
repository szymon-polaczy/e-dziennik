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
  <?php $title = "Sign In";
  include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <header class="navigation-header">
    <h2 class="logo"><a href="../wszyscy/index.php">school journal</a></h2>
    <p>Your next school journal</p>
  </header>

  <main>
    <form action="zadania/logowanie.php" method="post">
      <h2>Sign In</h2>
      <label for="logowanieEmail">Your email</label>
      <input id="logowanieEmail" type="email" placeholder="Email" name="email" required>
      <label for="logowanieHaslo">Your password</label>
      <input id="logowanieHaslo" type="password" placeholder="Hasło" name="haslo" required>
      <small>We do not share your e-mail with anyone and all your passwords are encrypted.</small>
      <?php
        if (isset($_SESSION['login_blad'])) {
          echo '<small>'.$_SESSION['login_blad'].'</small>';
          unset($_SESSION['login_blad']);
        }
      ?>
      <button type="submit">Sign In</button>
    </form>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>
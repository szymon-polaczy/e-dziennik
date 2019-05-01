<?php
  session_start();

  require_once "../php-classes/user.php";

  $class_user = new USER();

  if ($class_user->is_signed_in()) {
    header('Location: journal.php');
  }
?>
<!doctype html>
<head>
  <?php $site_title = "Sign In"; include("templates/head_tag_inside.php"); ?>
</head>
<body>
  <header class="navigation-header">
    <h1>school journal</h1>
  </header>
  <main>
    <form action="../php-tasks/sign_in.php" method="post">
      <label for="email-input">Email</label>
      <input id="email-input" name="email" type="email" placeholder="Email" required>
      <label for="password-input">Password</label>
      <input id="password-input" name="password" type="password" placeholder="Password" required>
      <button type="submit">Sign In</button>
      <?php
        if (isset($_SESSION['sign_in_message'])) {
          echo $_SESSION['sign_in_message'];
          unset($_SESSION['sign_in_message']);
        }
      ?>
    </form>
  </main>
  <footer>
    <h6>Author: <a href="https://szymonpolaczy.pl">Szymon Polaczy</a></h6>
  </footer>
</body>
</html>
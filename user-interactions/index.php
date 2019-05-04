<?php
  session_start();

  require_once "../php-classes/user.php";

  $class_user = new USER();

  if ($class_user->is_signed_in()) {
    header('Location: journal.php');
  }
?>
<!doctype html>
<?php $site_title = "Sign In"; include("templates/head_tag.php"); ?>
<body>
  <header class="navigation-header">
    <a href="index.php"><h2>school journal</h2></a>
  </header>

  <main>
    <form action="../php-tasks/sign_in.php" method="post">
      <h3>Sign In</h3>
      <label for="email-input">Email</label>
      <input id="email-input" name="email" type="email" placeholder="Enter your email here" required>
      <label for="password-input">Password</label>
      <input id="password-input" name="password" type="password" placeholder="Enter your password here" required>
      <button type="submit">Sign In</button>
      <?php
        if (isset($_SESSION['sign_in_message'])) {
          echo $_SESSION['sign_in_message'];
          unset($_SESSION['sign_in_message']);
        }
      ?>
    </form>
  </main>

  <?php include("templates/main_footer.php"); ?>
</body>
</html>
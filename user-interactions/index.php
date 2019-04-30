<?php
  session_start();
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
      <input id="email-input" name="email" type="email" placeholder="Email">
      <label for="password-input">Password</label>
      <input id="password-input" name="password" type="password" placeholder="Password">
      <button type="submit">Sign In</button>
    </form>
  </main>
  <footer>
    <h6>Author: <a href="https://szymonpolaczy.pl">Szymon Polaczy</a></h6>
  </footer>
</body>
</html>
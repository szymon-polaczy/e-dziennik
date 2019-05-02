<?php
  session_start();

  require_once "../php-classes/user.php";

  $class_user = new USER();

  if (!$class_user->is_signed_in()) {
    header('Location: index.php');
  }
?>
<!doctype html>
<head>
  <?php $site_title = "Home Page"; include("templates/head_tag_inside.php"); ?>
</head>
<body>
  <header class="navigation-header">
    <a href="journal.php"><h2>school journal</h2></a>
    <input type="checkbox" id="chk">
    <label for="chk" class="show-menu-btn"><i class="fas fa-bars"></i></label>

    <nav class="menu">
      <a href="../php-tasks/sign_out.php">sign out</a>
      <label for="chk" class="hide-menu-btn"><i class="fas fa-times"></i></label>
    </nav>
  </header>
  <main>
    You are signed in <br>
  </main>
  <footer>
    <h6>Author: <a href="https://szymonpolaczy.pl">Szymon Polaczy</a></h6>
  </footer>
</body>
</html>
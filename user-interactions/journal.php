<?php
  session_start();
  
  require_once "../php-classes/UserManager.php";

  $user_manager = new UserManager();

  if (!$user_manager->isSignedIn()) {
    header('Location: index.php');
  }
?>
<!doctype html>
<?php $site_title = "Home Page"; include("templates/head_tag.php"); ?>
<body>
  <?php include("templates/after_login_navigation_header.php"); ?>
  
  <main>
    You are signed in
  </main>
  
  <?php include("templates/main_footer.php"); ?>
</body>
</html>
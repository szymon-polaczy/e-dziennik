<?php
  session_start();
  
  require_once "../php-classes/AdministrationManager.php";

  $administration_manager = new AdministrationManager();

  if (!$administration_manager->isSignedIn()) {
    header('Location: index.php');
  }
?>
<!doctype html>
<?php $site_title = "Home Page"; include("templates/head_tag.php"); ?>
<body>
  <?php include("templates/after_login_navigation_header.php"); ?>
  
  <main>
    <h1>You are signed in</h1>
  </main>
  
  <?php include("templates/main_footer.php"); ?>
</body>
</html>
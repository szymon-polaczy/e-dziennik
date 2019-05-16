<?php
  session_start();

  require_once "../php-classes/user.php";

  $class_users = new USERS();

  if (!$class_users->is_signed_in()) {
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
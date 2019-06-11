<?php
  session_start();

  require_once "../php-tasks/files-needed/connect.php";

  require_once "../php-classes/PdoManager.php";
  require_once "../php-classes/UserManager.php";

  $pdo_manager = new PdoManager($db_user, $db_password, $db_name, $host);
  $user_manager = new UserManager();

  if (!$user_manager->isSignedIn()) {
    header('Location: index.php');
  }
?>
<!doctype html>
<?php $site_title = "Users"; include("templates/head_tag.php"); ?>
<body>
  <?php include("templates/after_login_navigation_header.php"); ?>
  
  <main>
    <h1>Users</h1>
    <section>
      <form class="add-form" id="add-form" action="" method="post">
        <div class="form-top">
          <h3>Add User</h3> 
          <button id="btn-hide-add-form" type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="form-wrapper">
          <label for="add-form-name">Name</label>
          <input id="add-form-name" name="name" placeholder="Add your user name" type="text">
          <label for="add-form-surname">Name</label>
          <input id="add-form-surname" name="surname" placeholder="Add your user surname" type="text">
          <label for="add-form-email">Email</label>
          <input id="add-form-email" name="email" placeholder="Add your user email" type="text">
          <label for="add-form-password">Password</label>
          <input id="add-form-password" name="password" placeholder="Add your user password" type="text">
          <button type="submit">Add</button>
          <!--PERMISSION-->
          <!--STUDENT HIDE-->
          <!--TEACHER HIDE-->
        </div>
      </form>
      <button id="btn-show-add-form"><i class="fas fa-plus"></i></button>
    </section>
  </main>
  
  <?php include("templates/main_footer.php"); ?>

  <script src="scripts/show_form.js"></script>
</body>
</html>
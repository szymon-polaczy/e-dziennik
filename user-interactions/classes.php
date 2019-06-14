<?php
  session_start();

  require_once "../php-tasks/files-needed/connect.php";

  require_once "../php-classes/PdoManager.php";
  require_once "../php-classes/UserManager.php";
  require_once "../php-classes/ClassManager.php";

  $pdo_manager = new PdoManager($db_user, $db_password, $db_name, $host);
  $user_manager = new UserManager();
  $class_manager = new ClassManager($pdo_manager);

  if (!$user_manager->isSignedIn()) {
    header('Location: index.php');
  }
?>
<!doctype html>
<?php $site_title = "Classes"; include("templates/head_tag.php"); ?>
<body>
  <?php include("templates/after_login_navigation_header.php"); ?>
  
  <main>
    <h1>Classes</h1>
    <section>
      <form class="add-form" id="add-form" action="" method="post">
        <div class="form-top">
          <h3>Add Class</h3> 
          <button id="btn-hide-add-form" type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="form-wrapper">
          <label for="add-form-name">Name</label>
          <input id="add-form-name" name="name" placeholder="Add your class name" type="text" required>
          <label for="add-form-description">Description</label>
          <input id="add-form-description" name="description" placeholder="Add your class description" type="text" required>
          <button type="submit">Add</button>
        </div>
      </form>
      <button id="btn-show-add-form"><i class="fas fa-plus"></i></button>
    </section>
  </main>
  
  <?php include("templates/main_footer.php"); ?>

  <script src="scripts/show_form.js"></script>
</body>
</html>
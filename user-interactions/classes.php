<?php
  session_start();

  require_once "../php-classes/pdo.php";
  require_once "../php-tasks/files-needed/connect.php";
  require_once "../php-classes/users.php";
  require_once "../php-classes/classes.php";

  $class_pdo_db = new PDO_DB($db_user, $db_password, $db_name, $host);
  $class_users = new USERS();
  $class_classes = new CLASSES();

  /*Coś tu nie działa*/
  $res = $class_classes->add($class_pdo_db, 'nazwa', 'opis');

  if ($res != "Good.")
    echo $res;
  else
    echo 'dobrze';

  if (!$class_users->is_signed_in()) {
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
          <input id="add-form-name" name="name" placeholder="Add your class name" type="text">
          <label for="add-form-description">Description</label>
          <input id="add-form-description" name="description" placeholder="Add your class description" type="text">
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
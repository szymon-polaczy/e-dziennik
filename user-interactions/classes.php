<?php
  session_start();

  if ($_SESSION['permissions'] != "a") {
    header('Location: journal.php');
    exit();
  }

  require_once "../php-tasks/files-needed/connect.php";
  require_once "../php-classes/PdoManager.php";
  require_once "../php-classes/AdministrationManager.php";
  require_once "../php-classes/ClassManager.php";

  $pdo_manager = new PdoManager(DB_USER, DB_PASSWORD, DB_NAME, HOST);
  $administration_manager = new AdministrationManager();
  $class_manager = new ClassManager($pdo_manager);

  if (!$administration_manager->isSignedIn()) {
    header('Location: index.php');
    exit();
  } else if (isset($_POST['name']) && isset($_POST['description'])) {
    $result = $class_manager->add($_POST['name'], $_POST['description']);
  }
?>
<!doctype html>
<?php $site_title = "Classes"; include("templates/head_tag.php"); ?>
<body>
  <?php include("templates/after_login_navigation_header.php"); ?>
  
  <main>
    <h1>Classes</h1>
    <?php 
      if(isset($result)) {
        echo '<p>'.$result.'</p>';
      }
    ?>
    <?php 
      $classes = $class_manager->getAll();
      if (is_array($classes)) {
        echo '<table style="margin-top: 10px;">';
          echo '<thead style="border-bottom: 1px solid #666;">';
            echo '<th>Name</th>';
            echo '<th>Description</th>';
          echo '</thead>';
          echo '<tbody>';
            foreach($classes as $class) {
              echo '<tr>';
                echo '<td style="padding: 10px 5px;">'.$class['name'].'</td>';
                echo '<td style="padding: 10px 5px;">'.$class['description'].'</td>';
              echo '</tr>';
            }
          echo '</tbody>';
        echo '</table>';
      } else {
        echo $classes;
      }
    ?>
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
</body>
</html>
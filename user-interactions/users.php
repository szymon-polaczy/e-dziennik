<?php
  session_start();

  require_once "../php-tasks/files-needed/connect.php";

  require_once "../php-classes/PdoManager.php";
  require_once "../php-classes/UserManager.php";
  require_once "../php-classes/ClassManager.php";
  require_once "../php-classes/RoomManager.php";

  $pdo_manager = new PdoManager($db_user, $db_password, $db_name, $host);
  $user_manager = new UserManager();
  $class_manager = new ClassManager($pdo_manager);
  $room_manager = new RoomManager($pdo_manager);

  $classes = $class_manager->getAll();
  $rooms = $room_manager->getAll();

  if (!$user_manager->isSignedIn()) {
    header('Location: index.php');
  }
?>
<!doctype html>
<html lang="en">
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
          <input id="add-form-name" name="name" placeholder="Add your user name" type="text" required>
          <label for="add-form-surname">Name</label>
          <input id="add-form-surname" name="surname" placeholder="Add your user surname" type="text" required>
          <label for="add-form-email">Email</label>
          <input id="add-form-email" name="email" placeholder="Add your user email" type="text" required>
          <label for="add-form-password">Password</label>
          <input id="add-form-password" name="password" placeholder="Add your user password" type="text" required>
          <label for="add-form-permission">Permission</label>
          <select id="add-form-permission" name="permission" required>
            <option></option>
            <option value="a">Administrator</option>
            <option value="t">Teacher</option>
            <option value="s">Student</option>
          </select>
          <!--STUDENT HIDE BOX-->
          <div id="add-form-student-hide-box">
            <?php if(count($classes) == 0) : ?>
              <small>There are no classes. To add a student you need to add classes first.</small>
            <?php else : ?>
              <label for="add-form-birthdate">Birthdate</label>
              <input id="add-form-birthdate" name="birthdate" type="date">
              <label for="add-form-class">Class</label>
              <select id="add-form-class" name="class">
                <option></option>
                <?php foreach($classes as $class) : ?>
                  <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
              </select>
            <?php endif; ?>
          </div>
          <!--TEACHER HIDE BOX-->
          <div id="add-form-teacher-hide-box">
            <?php if(count($rooms) == 0) : ?>
              <small>There are no rooms. To add a teacher you need to add rooms first.</small>
            <?php else : ?>
              <label for="add-form-room">Room</label>
              <select id="add-form-room" name="room">
                <option></option>
                <?php foreach($rooms as $room) : ?>
                  <option value="<?php echo $room['id']; ?>"><?php echo $room['name']; ?></option>
                <?php endforeach; ?>
              </select>
            <?php endif; ?>
          </div>
          <button type="submit">Add</button>
        </div>
      </form>
      <button id="btn-show-add-form"><i class="fas fa-plus"></i></button>
    </section>
  </main>
  
  <?php include("templates/main_footer.php"); ?>

  <script src="scripts/show_form.js"></script>
  <script src="scripts/show_permission_boxes.js"></script>
</body>
</html>
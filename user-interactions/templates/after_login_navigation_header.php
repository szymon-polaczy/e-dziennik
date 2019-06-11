<header class="navigation-header">
  <a href="journal.php"><h2>school journal</h2></a>
  <input type="checkbox" id="chk">
  <label for="chk" class="show-menu-btn"><i class="fas fa-bars"></i></label>

  <nav class="menu">
    <?php 
      if ($_SESSION['permissions'] == "a") {
        echo '<a href="classes.php">Classes</a>';
        echo '<a href="rooms.php">Rooms</a>';
        echo '<a href="subjects.php">Subjects</a>';
        echo '<a href="users.php">Users</a>';
      }
    ?>
    <a href="../php-tasks/sign_out.php">sign out</a>
    <label for="chk" class="hide-menu-btn"><i class="fas fa-times"></i></label>
  </nav>
</header>
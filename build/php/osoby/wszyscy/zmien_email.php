<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: index.php');
    exit();
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Change Email"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>  
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form action="zadania/zmiana_emailu.php" method="post">
      <h2>Change Email</h2>
      <label for="zmianaEmailuStary">Your Old Email</label>
      <input id="zmianaEmailuStary" type="email" placeholder="Old Email" name="semail" required>

      <label for="zmianaEmailuNowy">Your New Email</label>
      <input id="zmianaEmailuNowy" type="email" placeholder="New Email" name="nemail" required>

      <?php
        if (isset($_SESSION['zmiana_emailu'])) {
          echo '<small>'.$_SESSION['zmiana_emailu'].'</small>';
          unset($_SESSION['zmiana_emailu']);
        }
      ?>
      <button type="submit">Change Email</button>
    </form>

    <a href="dziennik.php"><button>Home Page</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

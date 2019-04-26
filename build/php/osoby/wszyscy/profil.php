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
  <?php $title = "Profile"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <a href="../wszyscy/zmien_email.php">Change Email</a><br>
    <a href="../wszyscy/zmien_haslo.php">Change Password</a><br>
    <a href="../wszyscy/zadania/wyloguj.php">Sign Out</a><br><br>

      Name: <?php echo $_SESSION['imie']; ?><br>
      Surname: <?php echo $_SESSION['nazwisko']; ?><br>
      Email: <?php echo $_SESSION['email']; ?><br>
      <?php
        if ($_SESSION['uprawnienia'] == "n")
          echo 'Room: '.$_SESSION['sala_nazwa'].'<br>';
        else if ($_SESSION['uprawnienia'] == "u")
          echo 'Class: '.$_SESSION['klasa_nazwa'].'<br>';
      ?>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

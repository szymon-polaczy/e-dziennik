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
  <?php $title = "Change Password"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>  
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form action="zadania/zmiana_hasla.php" method="post">
      <h2>Change Password</h2>
      <label for="zmianaHaslaStary">Your Old Password</label>
      <input id="zmianaHaslaStary" type="password" placeholder="Old Password" name="shaslo" required>
      <label for="zmianaHaslaNowe">Your New Password</label>
      <input id="zmianaHaslaNowe" type="password" placeholder="New Password" name="nhaslo" required>
      <?php
        if (isset($_SESSION['zmiana_hasla'])) {
          echo '<small id="logowaniePomoc" class="form-text uzytk-blad">'.$_SESSION['zmiana_hasla'].'</small>';
          unset($_SESSION['zmiana_hasla']);
        }
      ?>
      <button type="submit">Change Password</button>
    </form>

    <a href="dziennik.php"><button>Home Page</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

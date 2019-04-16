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
  <?php $title = "Strona główna"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <a class="dropdown-item" href="../wszyscy/zmien_email.php">ZMIEŃ EMAIL</a><br>
    <a class="dropdown-item" href="../wszyscy/zmien_haslo.php">ZMIEŃ HASŁO</a><br>
    <a class="dropdown-item" href="../wszyscy/zadania/wyloguj.php">WYLOGUJ</a><br><br>

      Imie: <?php echo $_SESSION['imie']; ?><br>
      Nazwisko: <?php echo $_SESSION['nazwisko']; ?><br>
      Email: <?php echo $_SESSION['email']; ?><br>
      <?php
        if ($_SESSION['uprawnienia'] == "n")
          echo 'Sala: '.$_SESSION['sala_nazwa'].'<br>';
        else if ($_SESSION['uprawnienia'] == "u")
          echo 'Klasa: '.$_SESSION['klasa_nazwa'].'<br>';
      ?>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

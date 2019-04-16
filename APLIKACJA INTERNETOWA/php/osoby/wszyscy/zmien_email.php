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
  <?php $title = "Zmień email"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>  
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form action="zadania/zmiana_emailu.php" method="post">
      <h2>Zmień Email</h2>
      <label for="zmianaEmailuStary">Wpisz Stary Email</label>
      <input id="zmianaEmailuStary" type="email" placeholder="Stary email" name="semail" required>

      <label for="zmianaEmailuNowy">Wpisz Nowy Email</label>
      <input id="zmianaEmailuNowy" type="email" placeholder="Nowy email" name="nemail" required>

      <?php
        if (isset($_SESSION['zmiana_emailu'])) {
          echo '<small id="logowaniePomoc" class="form-text uzytk-blad">'.$_SESSION['zmiana_emailu'].'</small>';
          unset($_SESSION['zmiana_emailu']);
        }
      ?>
      <button type="submit">Zmień Email</button>
    </form>

    <a href="dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

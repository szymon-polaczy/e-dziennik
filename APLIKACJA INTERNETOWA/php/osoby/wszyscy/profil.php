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
    <a class="dropdown-item" href="../wszyscy/zmien_dane.php">ZMIEŃ DANE</a>
    <a class="dropdown-item" href="../wszyscy/zadania/wyloguj.php">WYLOGUJ</a>

    <div>
      <br /><br />Imie: <span class="wartosc"><?php echo $_SESSION['imie']; ?></span><br />
      Nazwisko: <span class="wartosc"><?php echo $_SESSION['nazwisko']; ?></span><br />
      Email: <span class="wartosc"><?php echo $_SESSION['email']; ?></span><br />
      <?php
        if ($_SESSION['uprawnienia'] == "n")
          echo 'Sala: <span class="wartosc">'.$_SESSION['sala_nazwa'].'</span><br />';
        else if ($_SESSION['uprawnienia'] == "u")
          echo 'Klasa: <span class="wartosc">'.$_SESSION['klasa_nazwa'].'</span><br />';
      ?>
    </div>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

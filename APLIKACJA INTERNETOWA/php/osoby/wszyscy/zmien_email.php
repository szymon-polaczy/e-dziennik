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
    <div class="container p-0">
      <div class="row">
        <div class="col-md-10">
          <form action="zadania/zmiana_emailu.php" method="post">
            <h2>Zmień Email</h2>
            <div class="form-group">
              <label for="zmianaEmailuStary">Wpisz Stary Email</label>
              <input id="zmianaEmailuStary" class="form-control" type="email" placeholder="Stary email" name="semail" required>
            </div>
            <div class="form-group">
              <label for="zmianaEmailuNowy">Wpisz Nowy Email</label>
              <input id="zmianaEmailuNowy" class="form-control" type="email" placeholder="Nowy email" name="nemail" required>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['zmiana_emailu'])) {
                  echo '<small id="logowaniePomoc" class="form-text uzytk-blad">'.$_SESSION['zmiana_emailu'].'</small>';
                  unset($_SESSION['zmiana_emailu']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Zmień Email</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <a href="dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

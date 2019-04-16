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
  <?php $title = "Zmień hasło"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>  
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <div class="container p-0">
      <div class="row">
        <div class="col-md-10">
          <form action="zadania/zmiana_hasla.php" method="post">
            <h2>Zmień Hasło</h2>
            <div class="form-group">
              <label for="zmianaHaslaStary">Wpisz Stare Hasło</label>
              <input id="zmianaHaslaStary" class="form-control" type="password" placeholder="Stare hasło" name="shaslo" required>
            </div>
            <div class="form-group">
              <label for="zmianaHaslaNowe">Wpisz Nowe Hasło</label>
              <input id="zmianaHaslaNowe" class="form-control" type="password" placeholder="Nowe hasło" name="nhaslo" required>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['zmiana_hasla'])) {
                  echo '<small id="logowaniePomoc" class="form-text uzytk-blad">'.$_SESSION['zmiana_hasla'].'</small>';
                  unset($_SESSION['zmiana_hasla']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Zmień Hasło</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <a href="dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

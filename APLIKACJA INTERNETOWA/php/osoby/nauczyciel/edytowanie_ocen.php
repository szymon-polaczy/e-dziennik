<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'n')) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  if(!isset($_GET['wyb_przydzial']) || !isset($_GET['wyb_ocena'])) {
    header('Location: wybierz_przydzial.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  //wyciągam wartośc wybranej oceny
  $wyb_ocena = $_GET['wyb_ocena'];
  $sql = "SELECT wartosc FROM ocena WHERE id='$wyb_ocena'";

  $rezultat = $pdo->sql_value($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj ocenę"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form method="post" action="zadania/edytowanie_ocen.php">
      <h2>ZMIEŃ OCENĘ</h2>
      <label for="wyb_ocene">Zmień wartość oceny</label>
      <select name="wyb_wartosc" id="wyb_ocene" required>
        <?php
          $oceny = ['6', '6-', '5+', '5', '5-', '4+', '4', '4-', '3+', '3', '3-', '2+', '2', '2-', '1+', '1', '0'];

          foreach ($oceny as $oc) 
            echo '<option '.($oc == $rezultat? 'selected' : '').' value="'.$oc.'">'.$oc.'</option>';
        ?>
      </select>
      <input type="hidden" name="wyb_przydzial" value="<?php echo $_GET['wyb_przydzial']; ?>">
      <input type="hidden" name="wyb_ocena" value="<?php echo $wyb_ocena; ?>">
      <button type="submit">Zmień</button>
    </form>

    <a href="../wszyscy/dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);  

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if (!isset($_GET['wyb_klasa'])) {
    header('Location: admin_klasy.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  $wyb_klasa = $_GET['wyb_klasa'];
  $sql = "SELECT * FROM klasa WHERE id='$wyb_klasa'";
  $rezultat = $pdo->sql_record($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj klasę"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form action="zadania/edytowanie_klas.php" method="post">
      <h2>EDYTUJ KLASĘ</h2>
      <label for="zmianaNazwy">Zmień Nazwę</label>
      <input id="zmianaNazwy"small type="text" value="<?php echo $rezultat['nazwa']; ?>" name="nazwa" required/>
      <label for="zmianaOpisu">Zmień Opis</label>
      <input id="zmianaOpisu"small type="text" value="<?php echo $rezultat['opis']; ?>" name="opis" required/>
      <input type="hidden" value="<?php echo $wyb_klasa; ?>" name="wyb_klasa"/>
      <button type="submit">Zmień</button>
    </form>

    <a href="../wszyscy/dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

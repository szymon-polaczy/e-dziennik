<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if(!isset($_GET['wyb_przedmiot'])) {
    header('Location: admin_przydzialy.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $wyb_przedmiot = $_GET['wyb_przedmiot'];
  $sql = "SELECT przedmiot.nazwa FROM przedmiot WHERE id='$wyb_przedmiot'";
  $rezultat = $pdo->sql_record($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj przedmiot"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form method="post" action="zadania/edytowanie_przedmiotow.php">
      <h2>Edytuj przedmiot</h2>
      <label for="przedmiot-nazwa">Edytuj nazwę przedmiotu</label>
      <input name="nazwa" id="przedmiot-nazwa" value="<?php echo $rezultat['nazwa']; ?>" type="text" required>
      <input type="hidden" name="wyb_przedmiot" value="<?php echo $wyb_przedmiot?>">
      <button type="submit">Zmień</button>
    </form>

    <a href="../wszyscy/dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

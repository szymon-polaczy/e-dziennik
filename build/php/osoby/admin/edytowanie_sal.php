<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if (!isset($_GET['wyb_sala'])) {
    header('Location: admin_przydzialy.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  $wyb_sala = $_GET['wyb_sala'];
  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $sql = "SELECT * FROM sala WHERE id='$wyb_sala'";
  $rezultat = $pdo->sql_record($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj salę"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form method="post" action="zadania/edytowanie_sal.php">
      <h2>EDYTUJ SALE</h2>
      <label for="nazwa_sali">Edytuj nazwę sali</label>
      <input id="nazwa_sali" type="text" value="<?php echo $rezultat['nazwa']; ?>" name="nazwa" required/>
      <input type="hidden" name="wyb_sala" value="<?php echo $rezultat['id']; ?>">
      <button type="submit">Zmień</button>
    </form>

    <a href="../wszyscy/dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

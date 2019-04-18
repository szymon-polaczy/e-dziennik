<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);  

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if (!isset($_GET['wyb_osoba'])) {
    header('Location: admin_osoby.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  $user_adm = new Adm($pdo);

  $id_osoba = $_GET['wyb_osoba'];

  if (($_SESSION['edytowana'] = $user_adm->getUserById($id_osoba)) == NULL) {
    $_SESSION['edytowanie_osob'] = "Osoba o takim ID nie istnieje!";
    header('Location: admin_osoby.php');
    exit();
  }
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj osobę"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form action="zadania/edytowanie_osob.php" method="post">
      <h2>EDYTUJ OSOBĘ</h2>
      <label for="zmianaImienia">Edytuj Imię</label>
      <input id="zmianaImienia" type="text" value="<?php echo $_SESSION['edytowana']['imie']; ?>" name="imie" required/>
      <label for="zmianaNazwiska">Edytuj Nazwisko</label>
      <input id="zmianaNazwiska" type="text" value="<?php echo $_SESSION['edytowana']['nazwisko']; ?>" name="nazwisko" required/>
      <label for="zmianaEmailu">Edytuj Email</label>
      <input id="zmianaEmailu" type="email" value="<?php echo $_SESSION['edytowana']['email']; ?>" name="email" required/>
      <label for="zmianHasla">Edytuj Haslo</label>
      <input id="zmianHasla" type="password" value="<?php echo $_SESSION['edytowana']['haslo']; ?>" name="haslo" required/>
      <button type="submit">Zmień</button>
    </form>
    <a href="../wszyscy/dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

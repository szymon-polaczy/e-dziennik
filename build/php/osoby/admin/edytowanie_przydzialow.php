<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  } else if(!isset($_GET['wyb_przydzial'])) {
    header('Location: admin_przydzialy.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $adm = new Adm($pdo);

  $osoby = $adm->getUserByCategory("nauczyciel");
  $przedmioty = $adm->getAllFrom("przedmiot");
  $klasy = $adm->getAllFrom("klasa");

  $przydzial_id = $_GET['wyb_przydzial'];
  $sql = "SELECT * FROM przydzial WHERE id='$przydzial_id'";
  $edi = $pdo->sql_record($sql);
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Edytuj przydział"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <form method="post" action="zadania/edytowanie_przydzialow.php">
      <h2>Edytuj Przydział</h2>
      <label for="wybor_nauczyciela">Wybierz nauczyciela</label>
      <select name="wyb_nauczyciel" id="wybor_nauczyciela" required>
        <?php
          foreach ($osoby as $osoba)
            echo '<option '.($osoba['id'] == $edi['id_nauczyciel']? 'selected' : '').' value="'.$osoba['id'].'">Nauczyciel '.$osoba['imie'].' '.$osoba  ['nazwisko'].'</option>';
        ?>
      </select>
      <label for="wybor_przedmiotu">Wybierz przedmiot</label>
      <select name="wyb_przedmiot" id="wybor_przedmiotu" required>
        <?php
          foreach ($przedmioty as $sub)
            echo '<option '.($sub['id'] == $edi['id_przedmiot']? 'selected' : '').' value="'.$sub['id'].'">Przedmiot '.$sub['nazwa'].'</option>';
        ?>
      </select>
      <label for="wybor_klasy">Wybierz klasę</label>
      <select name="wyb_klasa" id="wybor_klasy" required>
        <?php
          foreach ($klasy as $cla)
            echo '<option '.($cla['id'] == $edi['id_klasa']? 'selected' : '').' value="'.$cla['id'].'">Klasa '.$cla['nazwa'].' | '.$cla['opis'].'</option>';
        ?>
      </select>
      <input type="hidden" name="edytowany_id" value="<?php echo $edi['id']; ?>">
      <button type="submit">Zmień</button>
    </form>

    <a href="../wszyscy/dziennik.php"><button>Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $adm = new Adm($pdo);

  $u_adm = $adm->getUserByCategory("administrator");
  $u_nau = $adm->getUserByCategory("nauczyciel");
  $u_ucz = $adm->getUserByCategory("uczen");

  $klasy = $adm->getAllFrom("klasa");
  $sale = $adm->getAllFrom("sala");
?>
<!doctype html>
<html lang="en">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "People";
  include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <button class="show-form-btn"><i class="fas fa-plus"></i></button>
      <form class="dis-form" action="zadania/dodawanie_osob.php" method="post">
        <label for="dodajImie">Name</label>
        <input id="dodajImie" type="text" placeholder="Name" name="imie" required />
        <label for="dodajImie">Surname</label>
        <input id="dodajImie" type="text" placeholder="Surname" name="nazwisko" required />
        <label for="dodajImie">Email</label>
        <input id="dodajImie" type="email" placeholder="Email" name="email" required />
        <label for="dodajImie">Password</label>
        <input id="dodajImie" type="password" placeholder="Password" name="haslo" required />
        <label for="nadajUprawnienia">Who are they</label>
        <select id="nadajUprawnienia" name="uprawnienia" required>
          <option></option>
          <option value="a">Administrators</option>
          <option value="n">Teachers</option>
          <option value="u">Learners</option>
        </select>

        <div class="invisible" id="nauczyciel-uzu">
          <?php
          if (count($sale) == 0) {
            echo '<small>There are no rooms</small>';
          } else {
            echo '<label for="wybierzSale">Rooms</label>';
            echo '<select id="wybierzSale" name="wyb_sala">';
              echo '<option></option>';
              foreach ($sale as $sala)
                echo '<option value="'.$sala['id'].'">'.$sala['nazwa'].'</option>';
            echo '</select>';
          }
          ?>
        </div>
        <div class="invisible" id="uczen-uzu">
          <?php
          if (count($klasy) == 0) {
            echo '<small>There are no classes</small>';
          } else {
            echo '<label for="dataUrodzenia">Birthdate</label>';
            echo '<input id="dataUrodzenia" type="date" name="data_urodzenia"/>';

            echo '<label for="wybierzKlase">Classes</label>';
            echo '<select id="wybierzKlase" name="wyb_klasa">';
              echo '<option></option>';
              foreach ($klasy as $klasa)
                echo '<option value="'.$klasa['id'].'">'.$klasa['nazwa'].'</option>';
            echo '</select>';
          }
          ?>
        </div>
        <button type="submit">Add</button>
      </form>
    </section>
    <section>
      <h2>See People</h2>
      <?php
        if (isset($_SESSION['dodawanie_osob'])) {
          echo '<small>'.$_SESSION['dodawanie_osob'].'</small>';
          unset($_SESSION['dodawanie_osob']);
        }
        
        if (isset($_SESSION['usuwanie_osob'])) {
          echo '<small>'.$_SESSION['usuwanie_osob'].'</small>';
          unset($_SESSION['usuwanie_osob']);
        }

        if (isset($_SESSION['edytowanie_osob'])) {
          echo '<small>'.$_SESSION['edytowanie_osob'].'</small>';
          unset($_SESSION['edytowanie_osob']);
        }

        echo '<h3>Administrators</h3>';
        $adm->showDataTable($u_adm, true, 'edytowanie_osob.php?wyb_osoba', 'usuwanie_osob.php?wyb_osoba');

        if (count($u_nau) > 1) {
          echo '<h3>Teachers</h3>';
          $adm->showDataTable($u_nau, true, 'edytowanie_osob.php?wyb_osoba', 'usuwanie_osob.php?wyb_osoba');
        }

        if (count($u_ucz) > 1) {
          echo '<h3>Learners</h3>';
          $adm->showDataTable($u_ucz, true, 'edytowanie_osob.php?wyb_osoba', 'usuwanie_osob.php?wyb_osoba');
        }
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button>Home Page</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>

  <!--ZOSTAW JQUERY-->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="../../../js/script.js" type="text/javascript"></script>
</body>
</html>
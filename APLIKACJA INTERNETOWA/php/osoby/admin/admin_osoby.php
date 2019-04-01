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
  
  //------------------------------------------------WYCIĄGANIE KLAS-----------------------------------------------//
  $sql = "SELECT * FROM klasa";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['klasy'] = $rezultat;

  //------------------------------------------------WYCIĄGANIE SAL-----------------------------------------------//
  $sql = "SELECT * FROM sala";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['sale'] = $rezultat;
?>
<!doctype html>
<html lang="en">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Osoby"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <div class="container p-0">
        <p>
          <button class="dodawanie-collapse-btn btn btn-dark" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Dodaj osobę
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <form action="zadania/dodawanie_osob.php" method="post">
            <div class="form-group">
              <label for="dodajImie">Wpisz Imię</label>
              <input id="dodajImie" class="form-control" type="text" placeholder="Imię" name="imie" required/>
            </div>
            <div class="form-group">
              <label for="dodajImie">Wpisz Nazwisko</label>
              <input id="dodajImie" class="form-control" type="text" placeholder="Nazwisko" name="nazwisko" required/>
            </div>
            <div class="form-group">
              <label for="dodajImie">Wpisz Email</label>
              <input id="dodajImie" class="form-control" type="email" placeholder="Email" name="email" required/>
            </div>
            <div class="form-group">
              <label for="dodajImie">Wpisz Hasło</label>
              <input id="dodajImie" class="form-control" type="password" placeholder="Hasło" name="haslo" required/>
            </div>
            <div class="form-group">
              <label for="nadajUprawnienia">Nadaj Uprawnienia</label>
              <select class="form-control" id="nadajUprawnienia"  name="uprawnienia" required>
                <option></option>
                <option value="a">Administrator</option>
                <option value="n">Nauczyciel</option>
                <option value="u">Uczeń</option>
              </select>
            </div>
            <div class="niewidoczne" id="nauczyciel-uzu">
              <?php
                if (count($_SESSION['sale']) == 0) {
                  echo '<span style="color: red;">Nie ma żadnej sali z którą można połączyć nauczyciela. Dodaj pierw sale!</span>';
                } else {
                  echo '<div class="form-group">';
                    echo '<label for="wybierzSale">Wybierz Salę</label>';
                    echo '<select class="form-control" id="wybierzSale" name="wyb_sala">';
                      echo '<option></option>';

                    foreach($_SESSION['sale'] as $sala)
                      echo '<option value="'.$sala['id'].'">'.$sala['nazwa'].'</option>';

                    echo '</select>';
                  echo '</div>';
                }
              ?>
            </div>
            <div class="niewidoczne" id="uczen-uzu">
              <?php
                if (count($_SESSION['klasy']) == 0) {
                  echo '<span style="color: red;">Nie ma żadnej klasy z którą można połączyć ucznia. Dodaj pierw klasy!</span>';
                } else {
                  echo '<div class="form-group">';
                    echo '<label for="dataUrodzenia">Wybierz Datę Urodzenia</label>';
                    echo '<input id="dataUrodzenia" class="form-control" type="date" name="data_urodzenia"/>';
                  echo '</div>';

                  echo '<div class="form-group">';
                    echo '<label for="wybierzKlase">Wybierz Klasę</label>';
                    echo '<select class="form-control" id="wybierzKlase" name="wyb_klasa">';
                     echo '<option></option>';

                    foreach($_SESSION['klasy'] as $klasa)
                      echo '<option value="'.$klasa['id'].'">'.$klasa['nazwa'].'</option>';

                    echo '</select>';
                  echo '</div>';
                }
              ?>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['dodawanie_osob'])) {
                  echo '<small class="form-text uzytk-blad">'.$_SESSION['dodawanie_osob'].'</small>';
                  unset($_SESSION['dodawanie_osob']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Dodaj</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <section>
      <h2>ZOBACZ OSOBY</h2>
      <?php
        if (isset($_SESSION['usuwanie_osob'])) {
          echo '<small   class="form-text uzytk-blad">'.$_SESSION['usuwanie_osob'].'</small>';
          unset($_SESSION['usuwanie_osob']);
        }

        if (isset($_SESSION['edytowanie_osob'])) {
          echo '<small   class="form-text uzytk-blad">'.$_SESSION['edytowanie_osob'].'</small>';
          unset($_SESSION['edytowanie_osob']);
        }

        echo '<h3>Administratorzy</h3>';
        $adm->showDataTable($u_adm, true, 'edytowanie_osob.php?wyb_osoba', 'usuwanie_osob.php?wyb_osoba');

        if (count($u_nau) > 1) {
          echo '<h3>Nauczyciele</h3>';
          $adm->showDataTable($u_nau, true, 'edytowanie_osob.php?wyb_osoba', 'usuwanie_osob.php?wyb_osoba');
        }

        if (count($u_ucz) > 1) {
          echo '<h3>Uczniowie</h3>';
          $adm->showDataTable($u_ucz, true, 'edytowanie_osob.php?wyb_osoba', 'usuwanie_osob.php?wyb_osoba');
        }
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="../../../js/script.js" type="text/javascript"></script>
</body>
</html>

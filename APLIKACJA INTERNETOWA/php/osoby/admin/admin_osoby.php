<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../user-adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  $user_adm = new User_Adm($pdo);

  $adm = $user_adm->getUserByCategory("administrator");
  $nau = $user_adm->getUserByCategory("nauczyciel");
  $ucz = $user_adm->getUserByCategory("uczen");
  
  //------------------------------------------------WYCIĄGANIE KLAS-----------------------------------------------//
  $sql = "SELECT * FROM klasa";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['klasy'] = $rezultat;

  //------------------------------------------------WYCIĄGANIE SAL-----------------------------------------------//
  $sql = "SELECT * FROM sala";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['sale'] = $rezultat;

  function showUserTable($who) {
    if (count($who) === 0)
      return NULL;

    echo '<table class="table">';
    echo '<thead class="thead-dark">';
      echo '<tr>';

        foreach($who[0] as $key => $val) {
          if ($key != "id_osoba" && $key != "id_klasa" && $key != "id_sala" && $key != "uprawnienia")
            echo '<th class="'.(is_numeric($val)? "tabela-liczby" : is_string($val)? "tabela-tekst" : '').'">'.$key.'</th>';
        }

        echo '<th class="tabela-zadania">opcje</th>'; 
      echo '</tr>';
    echo '</thead>';

    echo '<tbody>';

    foreach ($who as $per) {
      echo '<tr>';
        foreach($per as $key => $val) {
          if ($key == "haslo")
            echo '<td class="'.(is_numeric($val)? "tabela-liczby" : is_string($val)? "tabela-tekst" : '').'">'.$val[0].'</td>';
          else if ($key != "id_osoba" && $key != "id_klasa" && $key != "id_sala" && $key != "uprawnienia")
            echo '<td class="'.(is_numeric($val)? "tabela-liczby" : is_string($val)? "tabela-tekst" : '').'">'.$val.'</td>';
        }

        echo '<td class="tabela-zadania">';
          echo '<a href="edytowanie_osob.php?wyb_osoba='.$per['id'].'">Edytuj</a>';
          echo '<span>|</span>';
          echo '<a onclick="javascript:(confirm(\'Czy jesteś tego pewny?\')? window.location=\'zadania/usuwanie_osob.php?wyb_osoba='.$per['id'].'\':\'\')" href="#">Usuń</a>';
        echo '</td>';

      echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
  }
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
        //ZADANIA PHP
        if (isset($_SESSION['usuwanie_osob'])) {
          echo '<small   class="form-text uzytk-blad">'.$_SESSION['usuwanie_osob'].'</small>';
          unset($_SESSION['usuwanie_osob']);
        }

        if (isset($_SESSION['edytowanie_osob'])) {
          echo '<small   class="form-text uzytk-blad">'.$_SESSION['edytowanie_osob'].'</small>';
          unset($_SESSION['edytowanie_osob']);
        }

        echo '<h3>Administratorzy</h3>';
        showUserTable($adm);

        if (count($nau) > 1) {
          echo '<h3>Nauczyciele</h3>';
          showUserTable($nau);
        }

        if (count($ucz) > 1) {
          echo '<h3>Uczniowie</h3>';
          showUserTable($ucz); 
        }
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <footer class="fixed-bottom bg-dark glowna-stopka">
    <h6>Autor: Szymon Polaczy</h6>
  </footer>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="../../../js/script.js" type="text/javascript"></script>
</body>
</html>

<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);
  $adm = new Adm($pdo);

  $sql = "SELECT przydzial.id, klasa.nazwa AS `klasa nazwa`, przedmiot.nazwa AS `przedmiot nazwa`, osoba.imie, osoba.nazwisko
        FROM przydzial, klasa, przedmiot, nauczyciel, osoba
        WHERE przydzial.id_przedmiot=przedmiot.id AND przydzial.id_klasa=klasa.id
        AND przydzial.id_nauczyciel=nauczyciel.id_osoba AND nauczyciel.id_osoba=osoba.id";

  $rezultat = $pdo->sql_table($sql);
  $_SESSION['przydzialy'] = $rezultat;

  $nauczyciele = $adm->getUserByCategory("nauczyciel");
  $przedmioty = $adm->getAllFrom("przedmiot");
  $klasy = $adm->getAllFrom("klasa");
?>
<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Przydziały"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body class="index-body">
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <div class="container p-0">
        <p>
          <button class="dodawanie-collapse-btn btn btn-dark" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Dodaj przydziały
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <form method="post" action="zadania/dodawanie_przydzialow.php">
            <?php
              if (count($nauczyciele) == 0 || count($przedmioty) <= 0 || count($klasy) <= 0) {
                echo '<div class="przydzial-wiersz" style="color: #f33">NIE MA NAUCZYCIELI LUB PRZEDMIOTÓW LUB KLAS. DODAJ PIERW WSZYSTKIE ELEMENTY!</div>';
              } else {
                echo '<div class="form-group">';
                  echo '<label for="wyb_nauczyciela">Wybierz Nauczyciela</label>';
                  echo '<select name="wyb_nauczyciel" id="wyb_nauczyciela" class="form-control" required>';
                    echo '<option></option>';

                    foreach ($nauczyciele as $nauczyciel)
                      echo '<option value="'.$nauczyciel['id_osoba'].'">Nauczyciel '.$nauczyciel['imie'].' '.$nauczyciel['nazwisko'].'</option>';

                  echo '</select>';
                echo '</div>';

                echo '<div class="form-group">';
                  echo '<label for="wyb_przedmiot">Wybierz Przedmiot</label>';
                  echo '<select name="wyb_przedmiot" id="wyb_przedmiot" class="form-control" required>';
                    echo '<option></option>';

                    foreach ($przedmioty as $przedmiot)
                      echo '<option value="'.$przedmiot['id'].'">Przedmiot '.$przedmiot['nazwa'].'</option>';

                  echo '</select>';
                echo '</div>';

                echo '<div class="form-group">';
                echo '<label for="wyb_klase">Wybierz Klasę</label>';
                  echo '<select name="wyb_klasa" id="wyb_klase" class="form-control" required>';
                    echo '<option></option>';

                    foreach ($klasy as $klasa)
                      echo '<option value="'.$klasa['id'].'">Klasa '.$klasa['nazwa'].' | '.$klasa['opis'].'</option>';

                  echo '</select>';
                echo '</div>';

                echo '<div class="form-group form-inf">';
                  echo '<button type="submit" class="btn btn-dark">DODAJ</button>';

                  if (isset($_SESSION['dodawanie_przydzialow'])) {
                    echo '<p>'.$_SESSION['dodawanie_przydzialow'].'</p>';
                    unset($_SESSION['dodawanie_przydzialow']);
                  }

                echo '</div>';
              }
            ?>
          </form>
        </div>
      </div>
    </section>
    <section>
      <h2>ZOBACZ PRZYDZIAŁY</h2>
      <?php
        if (isset($_SESSION['edytowanie_przydzialow'])) {
          echo '<small class="form-text uzytk-blad">'.$_SESSION['edytowanie_przydzialow'].'</small>';
          unset($_SESSION['edytowanie_przydzialow']);
        }

        if (isset($_SESSION['usuwanie_przydzialow'])) {
          echo '<small class="form-text uzytk-blad">'.$_SESSION['usuwanie_przydzialow'].'</small>';
          unset($_SESSION['usuwanie_przydzialow']);
        }

        if (count($_SESSION['przydzialy']) > 0)
          $adm->showDataTable($_SESSION['przydzialy'], true, 'edytowanie_przydzialow.php?wyb_przydzial', 'usuwanie_przydzialow.php?wyb_przydzial');
        else
          echo '<p>Nie ma żadnych przydziałów</p>';
      ?>
    </section>

    <a href="../wszyscy/dziennik.php"><button class="btn btn-dark">Powrót do strony głównej</button></a>
  </main>

  <!--FOOTER INCLUDE-->
  <?php include("../../../html-templates/footer.php"); ?>
</body>
</html>

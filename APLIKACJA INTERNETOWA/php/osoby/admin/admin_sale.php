<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if(!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'a')) {
    header('Location: ../wszyscy/index.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";

  //------------------------------------------------WYCIĄGANIE SAL DO OBEJRZENIA-----------------------------------------------//

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  $sql = "SELECT * FROM sala";
  $rezultat = $pdo->sql_table($sql);
  $_SESSION['sale'] = $rezultat;
?>

<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Sale"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <div class="container p-0">
        <p>
          <button class="dodawanie-collapse-btn btn btn-dark" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Dodaj salę
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <form method="post" action="zadania/dodawanie_sal.php">
            <div class="form-group">
              <label for="nazwa_sali">Wpisz nazwę sali</label>
              <input class="form-control" id="nazwa_sali" type="text" placeholder="Nazwa" name="nazwa" required/>
            </div>
            <div class="form-group form-inf">
              <?php
                if (isset($_SESSION['dodawanie_sal'])) {
                  echo '<p>'.$_SESSION['dodawanie_sal'].'</p>';
                  unset($_SESSION['dodawanie_sal']);
                }
              ?>
              <button class="btn btn-dark" type="submit">Dodaj</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <section>
      <h2>ZOBACZ SALE</h2>
      <?php
        if (isset($_SESSION['usuwanie_sal'])) {
          echo '<p class="form-text uzytk-blad">'.$_SESSION['usuwanie_sal'].'</p>';
          unset($_SESSION['usuwanie_sal']);
        }
        if (isset($_SESSION['edytowanie_sal'])) {
          echo '<p class="form-text uzytk-blad">'.$_SESSION['edytowanie_sal'].'</p>';
          unset($_SESSION['edytowanie_sal']);
        }

        if (count($_SESSION['sale']) == 0) {
          echo '<p class="form-text uzytk-blad">ŻADNA SALA NIE ISTNIEJE W BAZIE</p>';
        } else {
          echo '<table class="table">';
          echo '<thead class="thead-dark">';
            echo '<tr>';
              echo '<th class="tabela-tekst">NAZWA</th>';
              echo '<th class="tabela-zadania">OPCJE</th>';
            echo '</tr>';
          echo '</thead>';

          echo '<tbody>';

          foreach ($_SESSION['sale'] as $sala) {
            echo '<tr>';
              echo '<td class="tabela-tekst">'.$sala['nazwa'].'</td>';
              echo '<td class="tabela-zadania">';
                echo '<a href="edytowanie_sal.php?wyb_sala='.$sala['id'].'">Edytuj</a>';
                echo '<span>|</span>';
                echo '<a onclick="javascript:(confirm(\'Czy jesteś tego pewny?\')? window.location=\'zadania/usuwanie_sal.php?wyb_sala='.$sala['id'].'\':\'\')" href="#">Usuń</a>';
              echo '</td>';
            echo '</tr>';
          }

          echo '</tbody>';
          echo '</table>';
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
</body>
</html>

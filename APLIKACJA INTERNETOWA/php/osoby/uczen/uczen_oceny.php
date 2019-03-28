<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  if (!isset($_SESSION['zalogowany']) || !($_SESSION['uprawnienia'] == 'u')) {
    header('Location: ../wszyscy/dziennik.php');
    exit();
  }

  require_once "../../polacz.php";
  require_once "../../wg_pdo_mysql.php";
  require_once "../../user-adm.php";

  $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

  $user_adm = new User_Adm($pdo);

  function showMarkTable($who) {
    if (count($who) === 0)
      return NULL;

    echo '<table class="table">';
    echo '<thead class="thead-dark">';
      echo '<tr>';

        foreach($who[0] as $key => $val)
          echo '<th class="'.(is_numeric($val)? "tabela-liczby" : is_string($val)? "tabela-tekst" : '').'">'.$key.'</th>';

      echo '</tr>';
    echo '</thead>';

    echo '<tbody>';

    foreach ($who as $per) {
      echo '<tr>';

        foreach($per as $key => $val)
          echo '<td class="'.(is_numeric($val)? "tabela-liczby" : is_string($val)? "tabela-tekst" : '').'">'.$val.'</td>';

      echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <!--INSIDE OF HEAD INCLUDE-->
  <?php $title = "Oceny"; include("../../../html-templates/inside-head.php"); ?>
</head>
<body>
  <!--HEADER INCLUDE-->
  <?php include("../../../html-templates/after-login-header.php"); ?>

  <main>
    <section>
      <h2>TWOJE OCENY</h2>
      <?php
        $id = $_SESSION['id'];
        $oceny = $user_adm->getUserMark($id);

        if (count($oceny) == 0) {
          echo '<p>Nie posiadasz żadnych ocen</p>';
        } else {
          showMarkTable($oceny);
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
</body>
</html>

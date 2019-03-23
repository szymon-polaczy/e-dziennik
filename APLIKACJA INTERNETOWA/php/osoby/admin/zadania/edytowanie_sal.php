<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  if (isset($_POST['nazwa']) && isset($_POST['wyb_sala'])) {
    $nazwa = $_POST['nazwa'];
    $wyb_sala = $_POST['wyb_sala'];
    $wszystko_ok = true;

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //Wyciąganie wybranej klasy
    $sql = "SELECT * FROM sala WHERE id='$wyb_sala'";
    $rezultat = $pdo->sql_record($sql);

    if ($nazwa != $rezultat['nazwa']) {
      if(strlen($nazwa) < 2 || strlen($nazwa) > 20) {
        $wszystko_ok = false;
        $_SESSION['edytowanie_sal'] = "Nazwa musi mieć pomiędzy 2 a 20 znaków!";
      }

      for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
        if ($nazwa == $_SESSION['sala'.$i]['nazwa']) {
          $wszystko_ok = false;
          $_SESSION['edytowanie_sal'] = "Sala o takiej nazwie już istnieje!";
          break;
        }
      }
    }

    if ($wszystko_ok) {
      $sql = "UPDATE sala SET nazwa='$nazwa' WHERE id='$wyb_sala'";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['edytowanie_sal'] = "Nazwa sali została edytowana!";
      else
        $_SESSION['edytowanie_sal'] = "Nazwa nie została edytowana!";
    }
  }

  header('Location: ../admin_sale.php');

<?php
  session_start();
  mysqli_report(MYSQLI_REPORT_STRICT);

  require_once "../../../polacz.php";
  require_once "../../../wg_pdo_mysql.php";

  if(isset($_POST['nazwa'])) {
    $nazwa = $_POST['nazwa'];
    $wszystko_ok = true;

    $pdo = new WG_PDO_Mysql($bd_uzytk, $bd_haslo, $bd_nazwa, $host);

    //Sprawdzanie długości nazwy
    if (strlen($nazwa) < 2 || strlen($nazwa) > 20) {
      $wszystko_ok = false;
      $_SESSION['dodawanie_sal'] = "Nazwa sali musi mieć pomiędzy 2 a 20 znaków!";
    }

    //Sprawdzanie czy istnieje taka nazwa w bazie
    for ($i = 0; $i < $_SESSION['ilosc_sal']; $i++) {
      if ($nazwa == $_SESSION['sala'.$i]['nazwa']) {
        $wszystko_ok = false;
        $_SESSION['dodawanie_sal'] = "Sala o takiej nazwie już istnieje!";
        break;
      }
    }

    //Po pozytywnym przejściu testów dodaję salę
    if($wszystko_ok) {
      $sql = "INSERT INTO sala VALUES (NULL, '$nazwa')";

      if ($rezultat = $pdo->sql_query($sql) > 0)
        $_SESSION['dodawanie_sal'] = "Nowa sala została dodana!";
      else
        $_SESSION['dodawanie_sal'] = "Sala nie została dodana!";
    }
  }

  header("Location:../admin_sale.php");

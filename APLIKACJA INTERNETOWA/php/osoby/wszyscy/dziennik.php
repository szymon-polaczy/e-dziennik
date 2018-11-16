<?php
  session_start();

  if(!isset($_SESSION['zalogowany'])) {
    header('Location: index.php');
    exit();
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Dziennik</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Redzik">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body>
  <header>
    <h1>STRONA GŁÓWNA PO LOGOWANIU</h1>
  </header>

  <main>
    <div class="profil">
      <h2 >PROFIL</h3>
      <p>Imie: <span class="wartosc"><?php echo $_SESSION['imie']; ?></span></p>
      <p>Nazwisko: <span class="wartosc"><?php echo $_SESSION['nazwisko']; ?></span></p>
      <p>Email: <span class="wartosc"><?php echo $_SESSION['email']; ?></span></p>
      <p>Haslo: <span class="wartosc"><?php echo substr($_SESSION['haslo'], 0, 4).'...'; ?></span></p>
      <p>Uprawnienia:
        <span class="wartosc">
          <?php
            if ($_SESSION['uprawnienia'] == "a") echo "Administrator";
            else if ($_SESSION['uprawnienia'] == "n") echo "Nauczyciel";
            else if ($_SESSION['uprawnienia'] == "u") echo "Uczeń";
          ?>
        </span>
      </p>
      <?php

      if ($_SESSION['uprawnienia'] == "n") {
        echo '<p>Nazwa Sali: <span class="wartosc">'.$_SESSION['sala_nazwa'].'</span></p>';

      } else if ($_SESSION['uprawnienia'] == "u") {
        echo '<p>Data urodzenia: <span class="wartosc">'.$_SESSION['data_urodzenia'].'</span></p>';
        echo '<p>Nazwa klasy: <span class="wartosc">'.$_SESSION['klasa_nazwa'].'</span></p>';
        echo '<p>Opis klasy: <span class="wartosc">'.$_SESSION['klasa_opis'].'</span></p>';
      }

      ?>
    <div>
  </main>

  <?php
    if ( $_SESSION['uprawnienia'] == "a") {
      echo '<h3>Rzeczy dostępne dla administratora</h3></br>';
      echo '<a href="../admin/adminklasy.php">KLASY</a></br>';
      echo '<a href="../admin/adminsale.php">SALE</a></br>';
      echo '<a href="../admin/adminprzedmioty.php">PRZEDMIOTY</a></br>';
      echo '<a href="../admin/adminosoby.php">OSOBY</a></br>';
      echo '<a href="../admin/adminprzydzialy.php">PRZYDZIAŁY</a>';

      echo '</br></br></br></br></br></br>';
    } else if ( $_SESSION['uprawnienia'] == "n") {
      echo '<h3>Rzeczy dostępne dla nauczyciela</h3></br>';
      echo '<a href="../nauczyciel/wybierzprzydzial.php">OCENY</a></br>';
      echo '<a href="../nauczyciel/nauczycielprzydzialy.php">ZOBACZ PRZYDZIAŁY</a>';

      echo '</br></br></br></br></br></br>';
    } else if ( $_SESSION['uprawnienia'] == "u") {
      echo '<h3>Rzeczy dostępne dla ucznia</h3></br>';
      echo '<a href="../uczen/uczenoceny.php">ZOBACZ OCENY</a></br>';
      echo '<a href="../uczen/uczenprzydzialy.php">ZOBACZ PRZYDZIAŁY</a>';

      echo '</br></br></br></br></br></br>';
    }
  ?>

  <h3>Rzeczy dostępne dla wszystkich</h3></br>
  <a href="zmiendane.php">ZMIEŃ DANE</a>
  </br>
  <a href="zadania/wyloguj.php">WYLOGUJ</a>

  </br></br></br></br></br></br>

  <footer>
    <h6>Autor: Szymon Polaczy</h6>
  </footer>
</body>
</html>

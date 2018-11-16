<?php
  session_start();

  if(isset($_SESSION['zalogowany'])) {
    header('Location: dziennik.php');
    exit();
  }
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>BDG DZIENNIK - Zaloguj Się</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="Redzik">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/style.css">
</head>
<body class="index-body">
  <header>
    <h1>BDG DZIENNIK</h1>
  </header>

  <main>
    <form action="zadania/logowanie.php" method="post">
      <h3>Zaloguj Się</h3>
      <input type="email" placeholder="Email" name="email"/>
      <input type="password" placeholder="Hasło" name="haslo"/>
      <button type="submit">Zaloguj</button>
      <div class="info">
        <?php
          if (isset($_SESSION['login_blad'])) {
            echo '<p>'.$_SESSION['login_blad'].'</p>';
            unset($_SESSION['login_blad']);
          }
        ?>
      </div>
    </form>
  </main>

  <footer>
    <h6>Autor: Szymon Polaczy</h6>
  </footer>
</body>
</html>

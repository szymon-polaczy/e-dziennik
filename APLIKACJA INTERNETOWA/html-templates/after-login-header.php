<header>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a href="dziennik.php" class="navbar-brand">BDG DZIENNIK</a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#glowneMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="glowneMenu" class="collapse navbar-collapse">
      <ul class="navbar-nav  ml-auto">
        <?php
          if ( $_SESSION['uprawnienia'] == "a") {
            echo '<li class="nav-item"><a href="../admin/admin_klasy.php" class="nav-link">KLASY</a></li>';
            echo '<li class="nav-item"><a href="../admin/admin_sale.php" class="nav-link">SALE</a></li>';
            echo '<li class="nav-item"><a href="../admin/admin_przedmioty.php" class="nav-link">PRZEDMIOTY</a></li>';
            echo '<li class="nav-item"><a href="../admin/admin_osoby.php" class="nav-link">OSOBY</a></li>';
            echo '<li class="nav-item"><a href="../admin/admin_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
          } else if ( $_SESSION['uprawnienia'] == "n") {
            echo '<li class="nav-item"><a href="../nauczyciel/wybierz_przydzial.php" class="nav-link">OCENY</a></li>';
            echo '<li class="nav-item"><a href="../nauczyciel/nauczyciel_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
          } else if ( $_SESSION['uprawnienia'] == "u") {
            echo '<li class="nav-item"><a href="../uczen/uczen_oceny.php" class="nav-link">OCENY</a></li>';
            echo '<li class="nav-item"><a href="../uczen/uczen_przydzialy.php" class="nav-link">PRZYDZIAŁY</a></li>';
          }
        ?>
        <li class="nav-item">
          <div class="dropdown">
            <a href="#" class="nav-item btn btn-dark dropdown-toggle" role="button" id="dropdownProfil"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              PROFIL
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
              <a class="dropdown-item disabled" href="#">Imie: <span class="wartosc"><?php echo $_SESSION['imie']; ?></span></a>
              <a class="dropdown-item disabled" href="#">Nazwisko: <span class="wartosc"><?php echo $_SESSION['nazwisko']; ?></span></a>
              <a class="dropdown-item disabled" href="#">Email: <span class="wartosc"><?php echo $_SESSION['email']; ?></span></a>
              <?php
                if ($_SESSION['uprawnienia'] == "n")
                  echo '<a class="dropdown-item disabled" href="#">Sala: <span class="wartosc">'.$_SESSION['sala_nazwa'].'</span></a>';
                else if ($_SESSION['uprawnienia'] == "u")
                  echo '<a class="dropdown-item disabled" href="#">Klasa: <span class="wartosc">'.$_SESSION['klasa_nazwa'].'</span></a>';
              ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="../wszyscy/zmien_dane.php">ZMIEŃ DANE</a>
              <a class="dropdown-item" href="../wszyscy/zadania/wyloguj.php">WYLOGUJ</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
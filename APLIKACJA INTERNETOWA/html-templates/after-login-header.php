<header>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a href="../wszyscy/dziennik.php" class="navbar-brand">E-DZIENNIK</a>
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
        <li class="nav-item"><a href="../wszyscy/profil.php" class="nav-link">PROFIL</a></li>
      </ul>
    </div>
  </nav>
</header>
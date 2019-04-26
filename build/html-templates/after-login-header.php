<header class="navigation-header">
  <h2 class="logo"><a href="../wszyscy/dziennik.php">school journal</a></h2>
  <input type="checkbox" id="chk">
  <label for="chk" class="show-menu-btn"><i class="fas fa-bars"></i></label>

  <nav class="menu">
    <?php
      if ($_SESSION['uprawnienia'] == "a") {
        echo '<a href="../admin/admin_klasy.php" class="nav-link">klasy</a>';
        echo '<a href="../admin/admin_sale.php" class="nav-link">sale</a>';
        echo '<a href="../admin/admin_przedmioty.php" class="nav-link">przedmioty</a>';
        echo '<a href="../admin/admin_osoby.php" class="nav-link">osoby</a>';
        echo '<a href="../admin/admin_przydzialy.php" class="nav-link">przydziały</a>';
      } else if ($_SESSION['uprawnienia'] == "n") {
        echo '<a href="../nauczyciel/wybierz_przydzial.php" class="nav-link">oceny</a>';
        echo '<a href="../nauczyciel/nauczyciel_przydzialy.php" class="nav-link">przydziały</a>';
      } else if ($_SESSION['uprawnienia'] == "u") {
        echo '<a href="../uczen/uczen_oceny.php" class="nav-link">oceny</a>';
        echo '<a href="../uczen/uczen_przydzialy.php" class="nav-link">przydziały</a>';
      }
    ?>
    <a href="../wszyscy/profil.php" class="nav-link">profil</a>

    <label for="chk" class="hide-menu-btn"><i class="fas fa-times"></i></label>
  </nav>
</header>
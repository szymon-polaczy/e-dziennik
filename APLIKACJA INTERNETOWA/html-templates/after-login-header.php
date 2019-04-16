<header class="navigation-header">
  <h2 class="logo"><a href="../wszyscy/dziennik.php">e-dziennik</a></h2>
  <input type="checkbox" id="chk">
  <label for="chk" class="show-menu-btn"><i class="fas fa-bars"></i></label>

  <nav class="menu">
    <?php
      if ($_SESSION['uprawnienia'] == "a") {
        echo '<a href="../admin/admin_klasy.php" class="nav-link">KLASY</a>';
        echo '<a href="../admin/admin_sale.php" class="nav-link">SALE</a>';
        echo '<a href="../admin/admin_przedmioty.php" class="nav-link">PRZEDMIOTY</a>';
        echo '<a href="../admin/admin_osoby.php" class="nav-link">OSOBY</a>';
        echo '<a href="../admin/admin_przydzialy.php" class="nav-link">PRZYDZIAŁY</a>';
      } else if ($_SESSION['uprawnienia'] == "n") {
        echo '<a href="../nauczyciel/wybierz_przydzial.php" class="nav-link">OCENY</a>';
        echo '<a href="../nauczyciel/nauczyciel_przydzialy.php" class="nav-link">PRZYDZIAŁY</a>';
      } else if ($_SESSION['uprawnienia'] == "u") {
        echo '<a href="../uczen/uczen_oceny.php" class="nav-link">OCENY</a>';
        echo '<a href="../uczen/uczen_przydzialy.php" class="nav-link">PRZYDZIAŁY</a>';
      }
    ?>
    <a href="../wszyscy/profil.php" class="nav-link">PROFIL</a>

    <label for="chk" class="hide-menu-btn"><i class="fas fa-times"></i></label>
  </nav>
</header>
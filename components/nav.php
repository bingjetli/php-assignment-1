<header class="mb-5">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">Lorsque</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <?php
            $is_active = $current_page === "home" ? " active" : "";
            echo "<a class='nav-link$is_active' href='/'>Home</a>";
            ?>
          </li>
          <li class="nav-item">
            <?php
            $is_active = $current_page === "create-cocktail" ? " active" : "";
            echo "<a class='nav-link$is_active' href='/create-cocktail.php'>Create Cocktail</a>";
            ?>
          </li>
          <li class="nav-item">
            <?php
            $is_active = $current_page === "create-ingredient" ? " active" : "";
            echo "<a class='nav-link$is_active' href='/create-ingredient.php'>Create Ingredient</a>";
            ?>
          </li>
          <li class="nav-item">
            <?php
            $is_active = $current_page === "list-ingredients" ? " active" : "";
            echo "<a class='nav-link$is_active' href='/list-ingredients.php'>List Ingredients</a>";
            ?>
          </li>
          <li class="nav-item">
            <?php
            $is_active = $current_page === "about" ? " active" : "";
            echo "<a class='nav-link$is_active' href='/about.php'>About</a>";
            ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
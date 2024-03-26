<?php
$user = null;

if (isset ($current_page) == false) {
  $current_page = "undefined-page";
}


if (isset ($_SESSION["user-token"])) {

  //If there is a user token session variable specified, then let's 
  //try to retreive the user data for this session token.
  if (isset ($user) == false) {
    require_once ("common/database.php");
    $user = fetchUserDetailsFromToken(isset ($pdo) == false ? getNewPDOInstance() : $pdo, $_SESSION["user-token"]);
  }
}
?>



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
            $is_active = $current_page === "about" ? " active" : "";
            echo "<a class='nav-link$is_active' href='/about.php'>About</a>";
            ?>
          </li>

          <?php
          if ($user != false && $user != null) {

            //If a user is currently logged in, let's check if they
            //are an administrator.
            if ($user["is_admin"] == true) {
              echo <<<EOT
                <li class="nav-item dropdown">
                  <a id="admin-links-dropdown-button" class="nav-link dropdown-toggle" href="#" role="button" aria-expanded="false" data-bs-toggle="dropdown">
                    Settings
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="admin-links-dropdown-button">
                    <li><a class="dropdown-item" href="/create-cocktail.php">Create Cocktail</a></li>
                    <li><a class="dropdown-item" href="/list-cocktails.php">List Cocktails</a></li>
                    <li><a class="dropdown-item" href="/create-ingredient.php">Create Ingredient</a></li>
                    <li><a class="dropdown-item" href="/list-ingredients.php">List Ingredients</a></li>
                  </ul>
                </li>
              EOT;
            }
          }
          ?>
        </ul>
      </div>
      <?php

      if ($user != false && $user != null) {

        //If the session token is valid, and there's user data associated
        //with the session token, then instead of showing the login/register
        //button, show the welcome message to the user.
        $user_display_name = $user["display_name"];
        echo <<<EOT
          <div class="navbar-text">
            <span>
              Welcome $user_display_name
            </span>
            <a class="btn btn-secondary-outline" href="/logout.php">Log out</a>
          </div>
        EOT;
      } else {

        //Otherwise, display the login and register button as usual.
        echo <<<EOT
          <div class="navbar-text">
            <a class="btn btn-secondary-outline" href="/login.php">Login</a>
            <a class="btn btn-secondary-outline" href="/register.php">Register</a>
          </div>
        EOT;
      }
      ?>
    </div>
  </nav>
</header>
<?php
$user = null;

if (isset ($_SESSION["user-token"]))
{

  //If there is a user token session variable specified, then let's 
  //try to retreive the user data for this session token.
  require_once ("common/database.php");
  $user = fetchUserDetailsFromToken(isset ($pdo) == false ? getNewPDOInstance() : $pdo, $_SESSION["user-token"]);
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
            $is_active = $current_page === "about" ? " active" : "";
            echo "<a class='nav-link$is_active' href='/about.php'>About</a>";
            ?>
          </li>
        </ul>
      </div>
      <?php

      if ($user != false && $user != null)
      {

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
      }
      else
      {

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
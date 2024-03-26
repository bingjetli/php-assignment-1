<?php
//Initialize the session so that the navbar can access the session details.
session_start();
require_once ("common/utils.php");
require_once ("common/database.php");
$pdo = getNewPDOInstance();


//Since this is an admin page, we need to verify that only admins can
//access this page.
if (isset ($_SESSION["user-token"]) == false) {

  //If there is no user-token, this means that there is no user logged in
  //which also means that the user is definitely not an administrator.
  //So we redirect them back to the main page.
  redirectToURL("/");
}


//Next, try to fetch the user details from the token variable.
$user = fetchUserDetailsFromToken($pdo, $_SESSION["user-token"]);


//Then check if the user is an administrator.
if ($user["is_admin"] == false) {

  //The user is not an administrator, so we'll redirect them back to the
  //main page.
  redirectToURL("/");
}

if (isset ($_POST['deleteIngredientFlag'])) {
  $ingredientId = $_POST['ingredientId'] ?? null;
  if ($ingredientId) {
    deleteIngredient($pdo, (int) $ingredientId);
  }

  // Redirect back to the ingredients list page or display a success message
  header("Location: list-ingredients.php");
  exit();
}

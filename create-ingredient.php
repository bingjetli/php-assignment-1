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


if (isset ($_POST["createIngredientFlag"]) == true) {
  addNewIngredient(
    $pdo,
    $_POST["ingredientName"],
    $_POST["ingredientCategory"]
  );


  require_once ("common/utils.php");
  redirectToURL("/");


  //since `redirectToURL()` kills the script after setting the Location header,
  //the PDO connect also closes the connection to the database.
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lorsque | Create Ingredient</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>

  <?php
  $current_page = "create-ingredient";
  require_once ("components/nav.php");
  ?>

  <form id="create-ingredient-form" method="post" action=""></form>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <h1 class="display-3 text-muted">Create a new Ingredient</h1>
      </div>
    </div>

    <div class="row justify-content-center mt-5">
      <div class="col-6">

        <div class="form-floating">
          <input form="create-ingredient-form" type="text" class="form-control" id="name-input" name="ingredientName"
            placeholder="#">
          <label for="name-input">Name of the Ingredient</label>
        </div>


        <div class="form-floating mt-2">
          <input form="create-ingredient-form" type="text" class="form-control" id="category-input"
            name="ingredientCategory" placeholder="#">
          <label for="category-input">Category of the Ingredient</label>
        </div>


        <div class="d-flex justify-content-center mt-4">
          <input form="create-ingredient-form" class="btn btn-primary btn-lg" type="submit" name="createIngredientFlag"
            value="Create">
        </div>
      </div>
    </div>
  </div>

  <?php require_once ("components/footer.php"); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>
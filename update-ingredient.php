<?php

//Import the database.php script, create the PDO instance for the entire page, check if the form has been submitted to update an ingredient?
if (isset($_POST["updateIngredientFlag"]) == true) {
  require_once("common/database.php");
  $pdo = getNewPDOInstance();

  $ingredientId = $_POST["ingredientId"];

  // Function to update ingredient details in the database
  updateIngredientDetails(
    $pdo,
    $_GET["id"],
    $_POST["ingredientName"],
    $_POST["ingredientCategory"]
  );

  require_once("common/utils.php");
  redirectToURL("/"); // Redirect back to ??? after updating, stops script execution so the database connection will close?
}

// Check if delete button is clicked
if (isset($_POST["deleteIngredientFlag"]) == true) {
  require_once("common/database.php");
  $pdo = getNewPDOInstance();

  deleteIngredient($pdo, $_POST["ingredientId"]);

  require_once("common/utils.php");
  redirectToURL("/"); // add redirect info here too!!
}

// Fills the form (if id is in url) + function that returns the details of the ingredient
$ingredient_details = null;
if (isset($_GET['id'])) {
  $ingredientId = $_GET['id'];
  require_once("common/database.php");
  $pdo = getNewPDOInstance();
  $ingredient_details = getIngredientDetailsById($pdo, $ingredientId);

  if ($ingredient_details != null) {
    $ingredient_details = $ingredient_details->fetch();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lorsque | Update Ingredient</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>

  <?php
  $current_page = "update-ingredient";
  require_once("components/nav.php");
  ?>

  <form id="update-ingredient-form" method="post" action="">
    <!-- "hidden" input to hold the ingredient ID for identifying which ingredient to update -->
    <input type="hidden" name="ingredientId" value="<?php echo htmlspecialchars($ingredientId); ?>">
  </form>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <h1 class="display-3 text-muted">Update Ingredient </h1>
        <!--<h1 class="display-3 text-muted">Update Ingredient <?php echo $_GET[$searchQuery] ?></h1>-->
      </div>
    </div>

    <div class="row justify-content-center mt-5">
      <div class="col-6">

        <div class="form-floating">
          <input form="update-ingredient-form" type="text" class="form-control" id="name-input" name="ingredientName" placeholder="#" value="<?php echo $ingredient_details['name']; ?>">
          <label for="name-input">Name of the Ingredient</label>
        </div>

        <div class="form-floating mt-2">
          <input form="update-ingredient-form" type="text" class="form-control" id="category-input" name="ingredientCategory" placeholder="#" value="<?php echo $ingredient_details['category']; ?>">
          <label for="category-input">Category of the Ingredient</label>
        </div>

        <div class="d-flex justify-content-center mt-4">
          <input form="update-ingredient-form" class="btn btn-primary btn-lg me-2" type="submit" name="updateIngredientFlag" value="Update">
        </div>
      </div>
    </div>
  </div>

  <?php require_once("components/footer.php"); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
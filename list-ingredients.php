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




// Initialize variable for search results
$searchResults = null;

// Check if there is a search query
if (isset ($_GET['searchQuery']) && !empty (trim($_GET['searchQuery']))) {
  $searchQuery = trim($_GET['searchQuery']);

  $searchResults = searchIngredientsByName($pdo, $searchQuery);
} else {
  // Get the full ingredients list if no search query available
  $ingredients = getIngredients($pdo);
  $ingredient_category_map = generateIngredientCategoryMap($ingredients);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lorsque | Ingredients List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <?php
  $current_page = "list-ingredients";
  require_once ("components/nav.php");
  ?>

  <div class="container mt-4">
    <h2>Ingredients List</h2>
    <!-- Search Form -->
    <form action="list-ingredients.php" method="GET" class="mb-4">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search ingredients" name="searchQuery">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
      </div>
    </form>
    <?php if (isset ($searchResults)): ?>
      <h3>Search Results</h3>
      <?php foreach ($searchResults as $ingredient): ?>
        <div>
          <?= htmlspecialchars($ingredient['name']); ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <?php foreach ($ingredient_category_map as $category => $ingredients): ?>
        <div class="mt-3">
          <h3>
            <?= htmlspecialchars($category); ?>
          </h3>
          <?php foreach ($ingredients as $ingredient): ?>
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
              <span>
                <?= htmlspecialchars($ingredient['name']); ?>
              </span>
              <div>
                <!-- Update button -->
                <a href="update-ingredient.php?id=<?= $ingredient['ingredient_id']; ?>"
                  class="btn btn-primary btn-sm">Update</a>
                <!-- Delete button -->
                <form method="POST" action="delete-ingredient-action.php" class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this ingredient?');">
                  <input type="hidden" name="ingredientId" value="<?= $ingredient['ingredient_id']; ?>">
                  <button type="submit" class="btn btn-danger btn-sm" name="deleteIngredientFlag">Delete</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <?php require_once ("components/footer.php"); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
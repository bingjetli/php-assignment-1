<?php
require_once("common/database.php");
$pdo = getNewPDOInstance();

if (isset($_POST['deleteIngredientFlag'])) {
  $ingredientId = $_POST['ingredientId'] ?? null;
  if ($ingredientId) {
    deleteIngredient($pdo, (int)$ingredientId);
  }

  // Redirect back to the ingredients list page or display a success message
  header("Location: list-ingredients.php");
  exit();
}

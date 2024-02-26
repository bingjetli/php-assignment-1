<?php

//Create the PDO instance for the entire page.
require_once("common/database.php");
$pdo = getNewPDOInstance();


if(isset($_POST["createCocktailFlag"]) == true){

  //First create the cocktail entry.
  addNewCocktail($pdo, $_POST["cocktailName"]);


  //Then retreive it's id.
  $cocktail_id = $pdo->lastInsertId();


  //Then loop through each ingredient in the ingredient list, and associate that
  //ingredient to the cocktail.
  $cocktail_ingredients = $_POST["cocktailIngredients"];
  foreach($cocktail_ingredients as $i_id => $i_data){
    addIngredientToCocktail($pdo, $cocktail_id, $i_id, $i_data["fraction"]);
  }


  //Redirect back to the homepage and kill the PDO connection.
  require_once("common/utils.php");
  redirectToURL("/");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lorsque | Create Cocktail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>

    <?php 
      $current_page = "create-cocktail";
      require_once("components/nav.php");
    ?>

    <?php
    if(isset($_POST["setFractionsFlag"]) === true){
      echo "<form id='set-fractions-form' method='post' action='' autocomplete='off'></form>";
    }
    else {
      echo "<form id='create-cocktail-form' method='post' action='' autocomplete='off'></form>";
    }
    ?>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-6">
          <h1 class="display-3 text-muted">Create a new Cocktail</h1>
        </div>
      </div>

      <div class="row justify-content-center mt-5">
        <div class="col-6">

          <?php
          if(isset($_POST["setFractionsFlag"]) === true){
            $cocktail_name = $_POST["cocktailName"];
            echo "<h2 class='text-muted fs-3 text-center'>Set Ingredient Proportions for <span class='display-6 d-block'>$cocktail_name</span></h2>";
            echo "<input form='set-fractions-form' ".
                        "type='hidden' ".
                        "value='$cocktail_name' ".
                        "name='cocktailName'>";


            echo "<div class='list-group mt-5'>";

            $cocktail_ingredients = $_POST["cocktailIngredients"];
            foreach($cocktail_ingredients as $i_id => $i_data){
              $i_name = $i_data["name"];


              echo "<div class='list-group-item d-flex justify-content-between'>".
                     "<div class='d-flex align-items-center'>".
                       "<h3 class='fs-6 text-muted'>$i_name</h3>".
                       "<input form='set-fractions-form' ".
                              "type='hidden' ".
                              "value='$i_name' ".
                              "name='cocktailIngredients[$i_id][name]'>".
                     "</div>".
                     "<div class='input-group w-auto'>".
                       "<input form='set-fractions-form' ".
                              "type='number' ".
                              "class='form-control text-center' ".
                              "id='$i_name-fractions-input' ".
                              "min='1' ".
                              "value='1' ".
                              "max='100' ".
                              "name='cocktailIngredients[$i_id][fraction]' ".
                              "placeholder='#'>".
                       "<label for='name-input' class='input-group-text text-center'>Parts</label>".
                     "</div>".
                   "</div>";
            }

            echo "</div>";
          }
          else {
            echo "<div class='form-floating'>".
                   "<input form='create-cocktail-form' ".
                          "type='text' ".
                          "class='form-control' ".
                          "id='name-input' ".
                          "name='cocktailName' ".
                          "placeholder='#'>".
                   "<label for='name-input'>Name of the Cocktail</label>".
                 "</div>";

            echo "<h2 class='text-muted fs-5 text-center mt-3'>Ingredients</h2>".
                 "<div class='accordion mt-3' id='ingredients-accordion'>";

            
            //Get all the ingredients from the database.
            $ingredients = getIngredients($pdo);
            $ingredient_category_map = generateIngredientCategoryMap($ingredients);


            foreach($ingredient_category_map as $c => $v){

              //First draw the header.
              echo "<div class='accordion-item'>";
              echo   "<h2 class='accordion-header'>".
                       "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#$c-accordion-body'>".
                         "$c".
                       "</button>".
                     "</h2>";

              
              //Then draw the accordion body.
              echo   "<div id='$c-accordion-body' class='accordion-collapse collapse' data-bs-parent='#ingredients-accordion'>".
                       "<div class='accordion-body'>";


              //Iterate through each of the ingredients in the specified category.
              foreach($v as $i){
                $i_name = $i["name"];
                $ingredient_id = $i["ingredient_id"];

                echo     "<input type='checkbox' class='btn-check' id='$i_name-checkbox' form='create-cocktail-form' name='cocktailIngredients[$ingredient_id][name]' value='$i_name'>";
                echo     "<label for='$i_name-checkbox' class='btn btn-outline-secondary mx-1 my-1'>".
                         "<!--".
                         "<img src='https://placehold.co/100' alt='$i_name-image'>".
                         "<p class='fw-bold text-uppercase'>$i_name</p>".
                         "-->".
                         "$i_name".
                         "</label>";
              }


              echo     "</div>".
                     "</div>";

              echo "</div>";

            }

            echo "</div>";

          }
          ?>

          <?php
          if(isset($_POST["setFractionsFlag"]) === true){
            echo "<div class='d-flex justify-content-center mt-5'>".
                   "<input ".
                     "form='set-fractions-form'".
                     "class='btn btn-primary btn-lg'".
                     "type='submit'".
                     "name='createCocktailFlag'".
                     "value='Create'>".
                 "</div>";
          }
          else{
            echo "<div class='d-flex justify-content-center mt-5'>".
                   "<input ".
                     "form='create-cocktail-form'".
                     "class='btn btn-primary btn-lg'".
                     "type='submit'".
                     "name='setFractionsFlag'".
                     "value='Next'>".
                 "</div>";
          }
          ?>
        </div>
      </div>
    </div>

    <?php require_once("components/footer.php");?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

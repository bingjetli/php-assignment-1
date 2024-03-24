<?php
require_once("common/database.php");
$pdo = getNewPDOInstance();


$recent_cocktails_stmt = getMostRecentCocktails($pdo);
$common_cocktails_stmt = getMostCommonCocktails($pdo);
//Sarah's 'most recent ingredients'
$recent_ingredients_stmt = getMostRecentIngredients($pdo); //fix this!!!


//Get the ingredients list.
$ingredients = getIngredients($pdo);
$ingredient_category_map = generateIngredientCategoryMap($ingredients);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height, target-densitydpi=medium-dpi">
  <title>Lorsque | Homepage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>

  <?php
  $current_page = "home";
  require_once("components/nav.php");
  ?>

  <div class="container">
    <div class="row mt-5">
      <div class="col">
        <div>
          <h2 class="display-6 text-muted">Recently Viewed</h2>
          <div>
            <?php
            foreach ($recent_cocktails_stmt as $c) {
              $c_name = $c["name"];
              $c_id = $c["cocktail_id"];


              echo "<a href='/cocktail-details.php?id=$c_id' class='btn btn-outline-secondary m-1'>" .
                "$c_name" .
                "</a>";
            }
            ?>
          </div>
        </div>

        <div class="mt-5">
          <h2 class="display-6 text-muted">Frequently Viewed</h2>
          <div>
            <?php
            foreach ($common_cocktails_stmt as $c) {
              $c_name = $c["name"];
              $c_id = $c["cocktail_id"];


              echo "<a href='/cocktail-details.php?id=$c_id' class='btn btn-outline-secondary m-1'>" .
                "$c_name" .
                "</a>";
            }
            ?>
          </div>
        </div>
      </div>


      <div class="col">

        <h2 class="display-6 text-muted">Search By Ingredients</h2>
        <form id="search-by-ingredients-form" method="get" action="/search-results.php"></form>


        <?php
        echo "<div class='accordion mt-3' id='ingredients-accordion'>";

        //Sarah's Most Recent Ingredient header
        echo "<div class='accordion-item'>";
        echo   "<h2 class='accordion-header'>" .
          "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseMostRecent' aria-expanded='true' aria-controls='collapseMostRecent'>" .
          "Most Recent Ingredients" .
          "</button>" .
          "</h2>";
        echo "<div id='collapseMostRecent' class='accordion-collapse collapse' aria-labelledby='headingMostRecent' data-bs-parent='#ingredients-accordion'>";
        echo "<div class='accordion-body'>";

        //Sarah's iteration through Most Recent Ingredient category.
        foreach ($recent_ingredients_stmt as $i) {

          $i_name = ($i["name"]);
          $ingredient_id = ($i["ingredient_id"]);

          echo     "<input type='checkbox' class='btn-check' id='recent-$ingredient_id-checkbox' form='search-by-ingredients-form' name='withIngredients[]' value='$ingredient_id' onchange='this.form.submit()'>";
          echo     "<label for='recent-$ingredient_id-checkbox' class='btn btn-outline-secondary mx-1 my-1'>
            $i_name
            </label>";
        }


        echo     "</div>" .
          "</div>";

        echo "</div>";


        foreach ($ingredient_category_map as $c => $v) {

          //First draw the header.
          echo "<div class='accordion-item'>";
          echo   "<h2 class='accordion-header'>" .
            "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#$c-accordion-body'>" .
            "$c" .
            "</button>" .
            "</h2>";

          //Then draw the accordion body.
          echo   "<div id='$c-accordion-body' class='accordion-collapse collapse' data-bs-parent='#ingredients-accordion'>" .
            "<div class='accordion-body'>";


          //Iterate through each of the ingredients in the specified category.
          foreach ($v as $i) {
            $i_name = $i["name"];
            $ingredient_id = $i["ingredient_id"];

            echo     "<input type='checkbox' class='btn-check' id='$i_name-checkbox' form='search-by-ingredients-form' name='withIngredients[]' value='$ingredient_id' onchange='this.form.submit()'>";
            echo     "<label for='$i_name-checkbox' class='btn btn-outline-secondary mx-1 my-1'>" .
              "<!--" .
              "<img src='https://placehold.co/100' alt='$i_name-image'>" .
              "<p class='fw-bold text-uppercase'>$i_name</p>" .
              "-->" .
              "$i_name" .
              "</label>";
          }


          echo     "</div>" .
            "</div>";

          echo "</div>";
        }

        echo "</div>";

        ?>

        <small class="mt-3 fs-light font-monospace d-block text-muted">
          NOTE : Selected ingredients stay selected until explicitly turned off. So
          selecting multiple ingredients means it will try to search for
          cocktails that will use all of the selected ingredients.
        </small>

        <small class="mt-2 fs-light font-monospace d-block text-muted">
          Also since sample data was randomly entered during development, some
          recipe proportions may be wildly incorrect and some ingredients aren't
          associated with a recipe.
        </small>

      </div>
    </div>
  </div>

  <?php
  require_once("components/footer.php");
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
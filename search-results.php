<?php
require_once("common/database.php");
$pdo = getNewPDOInstance();


//Get the ingredients list.
$ingredients = getIngredients($pdo);
$ingredient_category_map = generateIngredientCategoryMap($ingredients);


if(isset($_GET["withIngredients"]) === false){

  //Redirect and terminate script if this isn't supplied with the proper query parameter.
  require_once("common/utils.php");
  redirectToURL("/");
}


$incremental_search_stmt = incrementallySearchByIngredients($pdo, $_GET["withIngredients"]);
$match_count = count($_GET["withIngredients"]);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height, target-densitydpi=medium-dpi">
    <title>Lorsque | Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>

    <?php
    $current_page = "";
    require_once("components/nav.php");
    ?>

    <div class="container">
      <div class="row mt-5">
        <div class="col">

          <div>
            <h2 class="display-6 text-muted">Search Results</h2>
            <div>
              <?php
              $has_matches = false;


              foreach($incremental_search_stmt as $c){
                $c_name = $c["name"];
                $c_id = $c["cocktail_id"];
                $c_match = $c["matched"];

                if($c_match == $match_count){

                  $has_matches = true;
                  echo "<a href='/cocktail-details.php?id=$c_id' class='btn btn-outline-secondary m-1'>".
                       "$c_name".
                       "</a>";
                }
              }


              if($has_matches === false){
                echo "<h3 class='text-muted fw-bold w-75 mt-5 text-center'>Unable to Find Cocktails Using All Those Ingredients!</h3>";
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
                $is_checked = in_array($ingredient_id, $_GET["withIngredients"], false) ? "checked" : "";

                echo     "<input type='checkbox' class='btn-check' id='$i_name-checkbox' form='search-by-ingredients-form' name='withIngredients[]' value='$ingredient_id' onchange='this.form.submit()' $is_checked>";
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

          ?>

        </div>
      </div>
    </div>

    <?php
    require_once("components/footer.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

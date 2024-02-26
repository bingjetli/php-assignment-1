<?php
if(isset($_GET["id"]) === false){

  //Redirect the user back to the homepage if there is no id provided.
  require_once("common/utils.php");
  redirectToURL("/");
}


//Initialize a PDO instance for the page.
require_once("common/database.php");
$pdo = getNewPDOInstance();


//Then try to fetch the cocktail data for the specified id.
$cocktail_stmt = getCocktailById($pdo, intval($_GET["id"]));
$cocktail_details = $cocktail_stmt->fetch();


$ingredients_stmt = getCocktailIngredients($pdo, intval($_GET["id"]));


//Also update the usage count and last used.
incrementCocktailUsageCount($pdo, intval($_GET["id"]));
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height, target-densitydpi=medium-dpi">
    <title>
      <?php
        echo "Lorsque | ".$cocktail_details["name"]." Details";
      ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>

    <?php
    require_once("components/nav.php");
    ?>


    <div class="container">
      <div class="row">
        <div class="col">
          <h1>
            <?php
              echo $cocktail_details["name"];
            ?>
          </h1>
        </div>
      </div>


      <div class="row">
        <div class="col">
          <?php
            echo "<div class='list-group mt-5'>";

            foreach($ingredients_stmt as $i){
              $i_id = $i["ingredient_id"];
              $i_fraction = $i["fraction"];
              $i_name = $i["name"];


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
                              "disabled ".
                              "max='100' ".
                              "name='cocktailIngredients[$i_id][fraction]' ".
                              "placeholder='#'>".
                       "<label for='name-input' class='input-group-text text-center'>Parts</label>".
                     "</div>".
                   "</div>";
            }

            echo "</div>";
          ?>
        </div>
      </div>
    </div>



    <?php
    require_once("components/footer.php");
    exit();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

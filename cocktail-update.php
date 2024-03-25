<?php
  if(isset($_GET["id"]) === false){

    //Redirect the user back to the homepage if there is no id provided.
    require_once("common/utils.php");
    redirectToURL("/");
  }

  //Create the PDO instance for the entire page.
  require_once("common/database.php");
  $pdo = getNewPDOInstance();

  $cocktail_stmt = getCocktailById($pdo, intval($_GET["id"]));
  $cocktail_details = $cocktail_stmt->fetch();

  $ingredients_stmt = getCocktailIngredients($pdo, intval($_GET["id"]));

  if(isset($_POST["updateCocktailFlag"])){

    updateCocktail($pdo, intval($_POST['cocktail_id']), $_POST["cocktailName"], $_POST["cocktailNotes"]);

    
    $cocktail_ingredients = $_POST["cocktailIngredients"];
    foreach($cocktail_ingredients as $i_id => $i_data){
      updateIngredientToCocktail($pdo, $_POST['cocktail_id'], $i_id, $i_data["fraction"]);
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
    <title>
      <?php
        echo "Lorsque | ".$cocktail_details["name"]." Update";
      ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>

    <?php 
      $current_page = "cocktail-update";
      require_once("components/nav.php");
    ?>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-6">
          <h1 class="display-3 text-muted"><?php echo "Update ".$cocktail_details["name"]."" ?></h1>
        </div>
      </div>

      <div class="row justify-content-center mt-5">
        <div class="col-6">

          <?php
            echo '<a href="cocktail-delete.php?id='.$cocktail_details['cocktail_id'].'" class="btn btn-danger mb-2">Delete</a>';
            echo '<form method="post">';

            echo '<input type="hidden" name="cocktail_id" value="'.$cocktail_details['cocktail_id'].'">';

            echo "<div class='form-floating'>
                    <input type='text' class='form-control' id='name-input' name='cocktailName' value='".$cocktail_details['name']."'>
                    <label for='name-input'>Name of the Cocktail</label>".
                 "</div>";

            echo "<textarea class='form-control mt-2' ".
                           "placeholder='Notes & Comments...' ".
                           "rows='4' ".
                           "cols='50' ".
                           "style='resize:none' ".
                           "name='cocktailNotes'>".$cocktail_details["notes"]."</textarea>";

            echo "<h2 class='text-muted fs-5 text-center mt-3'>Ingredients</h2>";

            echo '<div class="list-group mt-5">';
              foreach($ingredients_stmt as $i){

                $i_id = $i["ingredient_id"];
                $i_fraction = $i["fraction"];
                $i_name = $i["name"];

                echo "<div class='list-group-item d-flex justify-content-between'>".
                          "<div class='d-flex align-items-center'>".
                            "<h3 class='fs-6 text-muted'>$i_name</h3>".

                          "</div>".
                          "<div class='input-group w-auto'>".
                            "<input type='number' class='form-control text-center' id='".$i_name."-fractions-input' min='1' value='".$i_fraction."' max='100' name='cocktailIngredients[".$i_id."][fraction]'>".
                            "<span class='input-group-text'>parts</span>".
                          "</div>".
                        "</div>";
              }

            echo "</div>";

            echo "<div class='d-flex justify-content-center mt-5'>".
                  "<input ".
                    "class='btn btn-primary btn-lg'".
                    "type='submit'".
                    "name='updateCocktailFlag'".
                    "value='Update'>".
                "</div>";

          ?>
        </div>
      </div>
    </div>

    <?php require_once("components/footer.php");?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

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


    if(isset($_POST["deleteCocktailFlag"])){

        deleteCocktail($pdo, $_POST['cocktail_id']);

        //Redirect back to the homepage and kill the PDO connection.
        require_once("common/utils.php");
        redirectToURL("/");

    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height, target-densitydpi=medium-dpi">
    <title>
      <?php
        echo "Lorsque | ".$cocktail_details["name"]." Delete";
      ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>

  <?php 
      $current_page = "cocktail-delete";
      require_once("components/nav.php");
    ?>


    <div class="container">
      <div class="row justify-content-center">
        <div class="col-6">
          <h1><?php echo $cocktail_details["name"]; ?></h1>
          <h2>Are you sure that you want to delete this cocktail?</h2>
          <form method="post">
            <div class="col text-center">
                <input type="hidden" name="cocktail_id" value="<?php echo $cocktail_details['cocktail_id'] ?>">
                <input class='btn btn-danger btn-lg' type='submit' name='deleteCocktailFlag' value='Delete'>
            </div>
          </form>
        </div>
      </div>

    <?php
    require_once("components/footer.php");
    exit();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

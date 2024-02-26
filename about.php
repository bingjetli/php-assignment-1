<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height, target-densitydpi=medium-dpi">
    <title>Lorsque | About </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>

    <?php
    $current_page = "about";
    require_once("components/nav.php");
    ?>

    <div class="container">
      <div class="row justify-content-center align-items-center">
        <div class="col-6">
          <h1>About This Project</h1>
          <p class="mt-3">
            This project started off as a feature demo idea for a Nightclub/Bar POS system.
            Bars tend to serve a number of different alcoholic beverages and depending
            on the establishment, a bartender would have to know how to make several
            different cocktails over the course of working there.
          </p>
          <p>
            Having worked in a bar myself, I've always wondered if their POS system
            could have been more intuitive. For example instead of selecting drinks
            by category, what if you can start by selecting the ingredients for 
            the drink first. This will then begin a search for all the cocktails
            that use the selected ingredient. Then by incrementally selecting 
            more ingredients, the results will get more and more specific to the
            exact drink that can be made with the selected ingredients.
          </p>
          <p>
            The idea of using this tool to train new bartenders also came to mind
            since starting off, it can be difficult to remember all the different
            recipes for the cocktails. By selecting what they can remember going
            into a drink, it will narrow the search results until they can find
            the right recipe which will contain the recipe along with the proportions.
          </p>
        </div>
      </div>
    </div>

    <?php
    require_once("components/footer.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

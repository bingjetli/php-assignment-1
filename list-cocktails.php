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


//Otherwise, proceed as normal.
$all_cocktails = getAllCocktails($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lorsque | List Cocktails</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <?php
  $current_page = "list-cocktails";
  require_once ("components/nav.php");
  ?>

  <div class="container mt-4">
    <div class="row mt-5">
      <div class="col">
        <h1 class="display-3">Cocktails List</h1>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col">


        <ul class="list-group list-group-flush">

          <?php

          foreach ($all_cocktails as $c) {
            $cocktail_name = $c["name"];
            $cocktail_id = $c["cocktail_id"];

            echo <<<EOT
              <li class="list-group-item">
                <div class="d-flex flex-row justify-content-between align-items-center py-2">
                  <div class="display-6">
                    $cocktail_name
                  </div>
                  <div class="btn-group" role="group">
                    <a class="btn btn-outline-secondary" href="/cocktail-update.php?id=$cocktail_id">Update</a>
                    <a class="btn btn-outline-danger" href="/cocktail-delete.php?id=$cocktail_id">Delete</a>
                  </div>
                </div>
              </li>
            EOT;
          }
          ?>

        </ul>

      </div>
    </div>

    <?php require_once ("components/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
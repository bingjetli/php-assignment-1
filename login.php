<?php
session_start();


//Create the PDO instance for the entire page.
require_once ("common/database.php");
$pdo = getNewPDOInstance();

$user = null;


if (isset ($_POST["loginFlag"]) == true)
{

  //Attempt to find the user inside the database.
  $user = loginUser($pdo, $_POST["username"], $_POST["password"]);

  if ($user != false)
  {

    //If a user is found with this username and password combination,
    //generate a new user token for this user.
    require_once ("common/utils.php");
    $_SESSION["user-token"] = generateUniqueIdentifier();


    //Update the session token for this user in the database.
    setUserSessionToken($pdo, $user["user_id"], $_SESSION["user-token"]);


    //Finally, redirect the user back to the homepage.
    redirectToURL("/");
  }

  //Otherwise, if the user was not found in the database, we should display
  //an error to the user.
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lorsque | Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>

  <?php
  $current_page = "login";
  require_once ("components/nav.php");
  ?>


  <form id="login-form" method="post" action=""></form>

  <div class="container">
    <div class="row">
      <div class="col-6">
        <h1 class="display-3 text-muted">Login</h1>
      </div>
    </div>


    <?php
    if ($user == false && isset ($_POST["loginFlag"]))
    {
      //Heredoc syntax. Expands variables, keeps indentation.
      echo <<<EOT
        <div class="row alert alert-warning" role="alert">
          <div class="col-6">
            Incorrect username and password combination!
          </div>
        </div>
      EOT;
    }
    ?>

    <div class="row mt-5">
      <div class="col-6">

        <div class="form-floating">
          <input form="login-form" type="text" class="form-control" id="username-input" minlength="3" maxlength="32"
            autocomplete="off" name="username" placeholder="#">
          <label for="username-input">Username</label>
        </div>


        <div class="form-floating mt-2">
          <input form="login-form" type="password" class="form-control" id="password-input" name="password"
            autocomplete="off" placeholder="#">
          <label for="password-input">Password</label>
        </div>


        <div class="d-flex justify-content-center mt-4">
          <input form="login-form" class="btn btn-primary btn-lg" type="submit" name="loginFlag" value="Login">
        </div>


      </div>
    </div>

  </div>


  <?php require_once ("components/footer.php"); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>
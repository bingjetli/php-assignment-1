<?php
session_start();


//Create the PDO instance for the entire page.
require_once ("common/database.php");
$pdo = getNewPDOInstance();

//Testing


$username_is_unique = null;
$display_name_is_unique = null;



if (isset ($_POST["registrationFlag"]) == true)
{
  require_once ("common/utils.php");


  $username_is_unique = isUsernameUnique($pdo, $_POST["username"]);
  $display_name_is_unique = isDisplayNameUnique($pdo, $_POST["display-name"]);


  if ($username_is_unique && $display_name_is_unique)
  {

    //Proceed with user creation if both the username and display name is unique.
    $hashed_password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $user_session_token = generateUniqueIdentifier();


    createNewUser(
      $pdo,
      $_POST["username"],
      $_POST["display-name"],
      $hashed_password,
      $user_session_token
    );


    $_SESSION["user-token"] = $user_session_token;


    redirectToURL("/");

  }

}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lorsque | Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>

  <?php
  $current_page = "register";
  require_once ("components/nav.php");
  ?>

  <form id="register-form" method="post" action=""></form>

  <div class="container">
    <div class="row">
      <div class="col-6">
        <h1 class="display-3 text-muted">Register</h1>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-6">

        <div class="form-floating">
          <input form="register-form" type="text" class="form-control" id="username-input" minlength="3" maxlength="32"
            name="username" placeholder="#" autocomplete="off">
          <label for="username-input">Username</label>
        </div>


        <div class="form-floating mt-2">
          <input form="register-form" type="text" class="form-control" id="display-name-input" minlength="3"
            maxlength="32" name="display-name" placeholder="#" autocomplete="off">
          <label for="display-name-input">Display Name</label>
        </div>


        <div class="form-floating mt-2">
          <input form="register-form" type="password" class="form-control" id="password-input" name="password"
            placeholder="#" autocomplete="off">
          <label for="password-input">Password</label>
        </div>


        <div class="d-flex justify-content-center mt-4">
          <input form="register-form" class="btn btn-primary btn-lg" type="submit" name="registrationFlag"
            value="Register">
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
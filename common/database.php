<?php
//---
//As it turns out, using PDO is the proper way to interact with MySQL in PHP.
//src : https://phpdelusions.net/pdo
//---

require_once (".dbenv.php");


function getNewPDOInstance(): PDO {
  $host = DB_HOST;
  $user = DB_USER;
  $pass = DB_PASS;
  $db = DB_NAME;

  //Specifies how the data is encoded during the process of sending or receiving
  //data from the database. "utf8mb4" is apparently the recommended value.
  $charset = "utf8mb4";


  $options = [
    //Specify that PDO should throw an exception if an error occurs during a query.
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,

    //Specify that PDO should fetch the resulting row as an associative array by
    //default. The fetch mode can also be set to another value on-demand during
    //the runtime of the script.
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,

    //Apparently, PDO can run queries by either (1) sending the prepared statement
    //with the placeholders to MySQL first, before sending the actual data when 
    //`execute()` is called; or (2) emulating the prepared statement functionality
    //but sending the to MySQL as proper SQL with all the data in place already
    //formatted. Both methods are equally secure in that they prevent SQL injections.
    //
    //Turning Emulated Prepared Statements on has the following benefits :
    // - Only 1 request is sent to MySQL instead of 2
    // - Multiple queries can be ran using 1 prepared statement
    // - Named parameters only have to be bound once, and can be used repeatedly.
    // - Some queries can only be ran when emulated mode is enabled; This is because
    //   native prepared statements only support certain query type.
    //
    //This feature can also be enabled at runtime, so here it's disabled by 
    //default and if the above benefits are needed, then enable it on-demand.
    \PDO::ATTR_EMULATE_PREPARES => false,
  ];

  $data_source_name = "mysql:host=$host;dbname=$db;charset=$charset";


  $pdo_instance = new PDO($data_source_name, $user, $pass, $options);

  return $pdo_instance;
}


function sendPreparedSQL($pdo_instance, $sql_statement, $args = null): PDOStatement {

  //Apparently, `mysqli_real_escape_string()` just escapes special characters in
  //the SQL string and the SQL Injection mitigation effect is a side-effect. The
  //actual way to prevent SQL Injections is to use `prepared statements`. 
  //src: https://phpdelusions.net/top
  $prepared_statement = $pdo_instance->prepare($sql_statement);
  $prepared_statement->execute($args);

  return $prepared_statement;
}


function addNewIngredient(PDO $pdo, string $name, string $category): void {
  $sql = "INSERT INTO tbl_ingredients (name, category) " .
    "VALUES (?, ?)";
  $args = [
    $name,
    $category
  ];
  $statement = sendPreparedSQL($pdo, $sql, $args);

  //Close the connection to the server from this cursor allowing other SQL
  //statements to be executed, but leaves the statement in a state where it
  //can be executed again.
  $statement->closeCursor();


  //For this INSERT statement, apparently we don't actually need to check for an error
  //because a PDOException will get thrown like we configured earlier. When this
  //happens, the script execution will be terminated.
  //
  //Although a try..catch block can be used if there's a specific error handling
  //procedure we want to use, such as rolling-back the transaction.
}


function addNewCocktail(PDO $pdo, string $name, string $notes = ""): void {
  $sql = "INSERT INTO tbl_cocktails (name, notes) " .
    "VALUES (?, ?)";
  $args = [
    $name,
    $notes,
  ];
  $statement = sendPreparedSQL($pdo, $sql, $args);

  //Close the connection to the server from this cursor allowing other SQL
  //statements to be executed, but leaves the statement in a state where it
  //can be executed again.
  $statement->closeCursor();
}



function addIngredientToCocktail(PDO $pdo, string $cocktail_id, string $ingredient_id, int $fraction): void {
  $sql = "INSERT INTO tbl_cocktail_ingredients (cocktail_id, ingredient_id, fraction) " .
    "VALUES (?, ?, ?)";
  $args = [
    $cocktail_id,
    $ingredient_id,
    $fraction
  ];
  $statement = sendPreparedSQL($pdo, $sql, $args);

  //Close the connection to the server from this cursor allowing other SQL
  //statements to be executed, but leaves the statement in a state where it
  //can be executed again.
  $statement->closeCursor();
}


function incrementCocktailUsageCount(PDO $pdo, int $cocktail_id): void {
  $sql = "UPDATE tbl_cocktails " .
    "SET times_used = times_used + 1, " .
    "last_used = CURRENT_TIMESTAMP " .
    "WHERE cocktail_id = ?";
  $args = [
    $cocktail_id,
  ];
  $statement = sendPreparedSQL($pdo, $sql, $args);

  //Close the connection to the server from this cursor allowing other SQL
  //statements to be executed, but leaves the statement in a state where it
  //can be executed again.
  $statement->closeCursor();
}


function getIngredients(PDO $pdo, string $category = null): PDOStatement {

  //First check if the category filters were defined.
  if ($category === null) {

    //Since there is no category filtering specified, we can run a simple
    //query to the database and return the PDO Statement.
    $sql = "SELECT * FROM tbl_ingredients";

    return $pdo->query($sql);
  }


  $sql = "SELECT * FROM tbl_ingredients WHERE category = ?";
  return sendPreparedSQL($pdo, $sql, [$category]);
}


function getAllCocktails(PDO $pdo): PDOStatement {
  $sql = "SELECT * FROM tbl_cocktails";
  return $pdo->query($sql);
}


//Sarah's Update Ingredient Page Functions (1)
function getIngredientDetailsById(PDO $pdo, int $id): PDOStatement {
  $sql = "SELECT * FROM tbl_ingredients WHERE ingredient_id = ?";
  $stmt = sendPreparedSQL($pdo, $sql, [$id]);

  return $stmt;
}


//Sarah's Update Ingredient Page Functions (2)
function updateIngredientDetails(PDO $pdo, int $id, string $name, string $category): void {
  $sql = "UPDATE tbl_ingredients SET name = ?, category = ? WHERE ingredient_id = ?";
  $args = [
    $name,
    $category,
    $id
  ];
  $statement = sendPreparedSQL($pdo, $sql, $args);

  $statement->closeCursor();
}


//Sarah's Update Ingredient Page DELETE Function
function deleteIngredient(PDO $pdo, int $ingredientId): void {
  $sql = "DELETE FROM tbl_ingredients WHERE ingredient_id = ?";
  sendPreparedSQL($pdo, $sql, [$ingredientId]);
}


//Sarah's Search Function
function searchIngredientsByName(PDO $pdo, string $searchQuery): PDOStatement {
  $sql = "SELECT * FROM tbl_ingredients WHERE name LIKE :searchQuery";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['searchQuery' => "%" . $searchQuery . "%"]);
  return $stmt;
}


//Sarah's index.php get recent ingredients function
function getMostRecentIngredients(PDO $pdo, int $max_result = 10): PDOStatement {
  $sql = "SELECT * FROM tbl_ingredients ORDER BY last_used DESC LIMIT ?";
  return sendPreparedSQL($pdo, $sql, [$max_result]);
}



function getAllIngredientCategories(PDO $pdo): PDOStatement {
  $sql = "SELECT category " .
    "FROM tbl_ingredients " .
    "GROUP BY category";


  return $pdo->query($sql);
}


function getCocktailById(PDO $pdo, int $id): PDOStatement {
  $sql = "SELECT * FROM tbl_cocktails WHERE cocktail_id = ?";


  return sendPreparedSQL($pdo, $sql, [$id]);
}


function getCocktailIngredients(PDO $pdo, int $id): PDOStatement {
  $sql = "SELECT * " .
    "FROM tbl_cocktail_ingredients " .
    "INNER JOIN tbl_ingredients ON tbl_cocktail_ingredients.ingredient_id=tbl_ingredients.ingredient_id " .
    "WHERE tbl_cocktail_ingredients.cocktail_id = ?";

  return sendPreparedSQL($pdo, $sql, [$id]);
}


function getMostRecentCocktails(PDO $pdo, int $max_result = 10): PDOStatement {
  $sql = "SELECT * " .
    "FROM tbl_cocktails " .
    "ORDER BY last_used DESC " .
    "LIMIT ?";


  return sendPreparedSQL($pdo, $sql, [$max_result]);
}



function getMostCommonCocktails(PDO $pdo, int $max_result = 10): PDOStatement {
  $sql = "SELECT * " .
    "FROM tbl_cocktails " .
    "ORDER BY times_used DESC " .
    "LIMIT ?";


  return sendPreparedSQL($pdo, $sql, [$max_result]);
}


function incrementallySearchByIngredients(
  PDO $pdo,
  array $ingredient_ids,
  int $max_result = 10,
): PDOStatement {

  //First, generate the placeholder list for the prepared statement.
  $placeholder_list = implode(", ", array_map(fn($i): string => '?', $ingredient_ids));


  $sql = "SELECT COUNT(*) AS matched, tbl_cocktails.cocktail_id, tbl_cocktails.name " .
    "FROM tbl_cocktail_ingredients " .
    "INNER JOIN tbl_cocktails ON tbl_cocktail_ingredients.cocktail_id=tbl_cocktails.cocktail_id " .
    "WHERE ingredient_id IN ($placeholder_list) " .
    "GROUP BY tbl_cocktail_ingredients.cocktail_id " .
    "LIMIT ?";


  return sendPreparedSQL($pdo, $sql, [...$ingredient_ids, $max_result]);
}


function isUsernameUnique(PDO $pdo, string $username): bool {
  $sql = "SELECT * FROM tbl_users WHERE user_name = ?";

  $result = sendPreparedSQL($pdo, $sql, [$username]);


  //Check if the SQL had any results, if it failed or returned no results,
  //the value of this variable will be false, and we'll know that the username 
  //is unique. Otherwise, if there were results, then the value will not be false
  //and we'll know that the username is not unique.
  return $result->fetch() == false ? true : false;
}


function isDisplayNameUnique(PDO $pdo, string $display_name): bool {
  $sql = "SELECT * FROM tbl_users WHERE display_name = ?";

  $result = sendPreparedSQL($pdo, $sql, [$display_name]);


  return $result->fetch() == false ? true : false;
}


function createNewUser(
  PDO $pdo,
  string $username,
  string $display_name,
  string $password,
  string $session_token
): void {
  $sql = "INSERT INTO tbl_users (user_name, display_name, password, session_token) " .
    "VALUES (?, ?, ?, ?)";


  $args = [
    $username,
    $display_name,
    $password,
    $session_token,
  ];


  $statement = sendPreparedSQL($pdo, $sql, $args);


  //Close the connection to the server from this cursor allowing other SQL
  //statements to be executed, but leaves the statement in a state where it
  //can be executed again.
  $statement->closeCursor();
}



function loginUser(PDO $pdo, string $username, string $password): bool|array {
  $sql = "SELECT * FROM tbl_users WHERE user_name = ?";
  $result = sendPreparedSQL($pdo, $sql, [$username])->fetch();

  if ($result != false) {

    //If there was a user with this username in the database. Then try
    //to verify the password against the hashed password stored in the
    //database.
    $is_verified_user = password_verify($password, $result["password"]);
    if ($is_verified_user == true) {

      //If the password matches the hashed password, then return the 
      //userdata.
      return $result;
    } else {

      //Otherwise return false
      return false;
    }
  }

  //Return false by default, this indicates that we didn't find the user
  //inside the database.
  return false;
}


function fetchUserDetailsFromToken(PDO $pdo, string $session_token): bool|array {
  $sql = "SELECT * FROM tbl_users WHERE session_token = ?";
  return sendPreparedSQL($pdo, $sql, [$session_token])->fetch();
}


function setUserSessionToken(PDO $pdo, int $user_id, string $session_token): void {
  $sql = "UPDATE tbl_users " .
    "SET session_token = ? " .
    "WHERE user_id = ?";


  $args = [
    $session_token,
    $user_id,
  ];


  $statement = sendPreparedSQL($pdo, $sql, $args);
  $statement->closeCursor();
}


function generateIngredientCategoryMap(PDOStatement $ingredients_stmt): array {
  $map = array();

  foreach ($ingredients_stmt as $i) {
    $category = $i["category"];


    //First check if the map has this key already.
    if (isset ($map[$category]) == true) {

      //this category is already defined in the map. So we can just append the
      //value into the array.
      $map[$category][] = $i;
    } else {

      //This category hasn't been defined in the map as yet, so we'll create a
      //new array with the value initialized in it.
      $map[$category] = array($i);
    }
  }

  return $map;
}



# Doesn't work, TO-SELF: Fix this function at some point...
function auditPDO(callable $db_function, ...$args) {
  echo "Auditing $db_function";

  try {
    $db_function(...$args);
  } catch (PDOException $pdoe) {
    echo "Exception Occured : " . $pdoe->getMessage() . "\n -- $args";
  }
}

function updateCocktail(PDO $pdo, int $cocktail_id, string $name, string $notes = ""): void {
  $sql = "UPDATE tbl_cocktails SET name = ?, notes = ? WHERE cocktail_id = ?";
  $args = [
    $name,
    $notes,
    $cocktail_id
  ];
  $statement = sendPreparedSQL($pdo, $sql, $args);

  //Close the connection to the server from this cursor allowing other SQL
  //statements to be executed, but leaves the statement in a state where it
  //can be executed again.
  $statement->closeCursor();
}

function updateIngredientToCocktail(PDO $pdo, string $cocktail_id, string $ingredient_id, int $fraction): void {
  $sql = "UPDATE tbl_cocktail_ingredients SET fraction = ? WHERE cocktail_id = ? AND ingredient_id  = ?";
  $args = [
    $fraction,
    $cocktail_id,
    $ingredient_id
  ];
  $statement = sendPreparedSQL($pdo, $sql, $args);

  //Close the connection to the server from this cursor allowing other SQL
  //statements to be executed, but leaves the statement in a state where it
  //can be executed again.
  $statement->closeCursor();
}

function deleteCocktail(PDO $pdo, int $cocktail_id): void {
  $sql = "DELETE FROM tbl_cocktails WHERE cocktail_id = ?";
  $args = [
    $cocktail_id
  ];
  $statement = sendPreparedSQL($pdo, $sql, $args);

  $statement->closeCursor();
}

?>
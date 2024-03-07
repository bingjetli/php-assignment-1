# How do I Access the Database in my PHP Pages?

Inside the `<?php` tag, first import the `database.php` script using :

```php
require_once("common"/database.php);

```


Then get a new PDO instance using `getNewPDOInstance()` and save it to a variable :
```php
$pdo = getNewPDOInstance();

```


Now, you can pass this PDO instance to whatever database function you need to call. 

For example :

```php
getMostRecentCocktails($pdo);

```

&nbsp;
&nbsp;


# Why `require_once()` ?

`require()` and `include()` are both statements that can be used to import scripts from other PHP files. 

The difference is `require()` will kill the script and return an error if it fails to import the file. 

`include()` will just generate a warning and continue running the script.


&nbsp;


Both `include()` and `require()` have variants called `require_once()` and `include_once()`.

These variants specify that each PHP file should be imported only once in the current script.

So if the external PHP script was already imported in the current file, it doesn't do a double-import.


&nbsp;


We want to specify that the current page should stop running and throw an error if it fails to 
import our `database.php` since the entire page depends on it.


&nbsp;
&nbsp;


# Ok, What now After I call the Database Functions?

Most "get" functions return a [PDOStatement](https://www.php.net/manual/en/class.pdostatement.php).

You can take a look at the *Table of Contents* in the documentation page linked above to see what methods you can use on it.

In general, if the SQL query was supposed to return a single query, you can use `fetch()` to get the row like so :

```php

//Then try to fetch the cocktail data for the specified id.
$cocktail_stmt = getCocktailById($pdo, intval($_GET["id"]));
$cocktail_details = $cocktail_stmt->fetch();


//...and then use it at some point using :
echo "Lorsque | ".$cocktail_details["name"]." Details";

```


&nbsp;


If you're expecting the function to return a set of SQL rows instead, you can loop through it using `foreach()` :
```php
//This will give us a list of SQL Rows...
$common_cocktails_stmt = getMostCommonCocktails($pdo);


//So we loop through it like so...
foreach($common_cocktails_stmt as $c){

    //And we can use it like so...
    echo $c["cocktail_id"]
}

```

&nbsp;


In general, there shouldn't be a case where you need to get only 1 row from a list of SQL rows, 
because you would either use a function that selects 1 row or create a function that does that 
and use the `fetch()` method.

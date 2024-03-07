# Code Style

To make it so that our codebase looks consistent and readable, I ask that we follow these guidelines.

I think compared to larger projects, these guidelines are quite minimal.


# Variables

Variables should be written in `snake_case`. So all lowercase and whitespaces are replaced with an underscore.

```php
//For example
$my_amazing_variable_name_here = "whoa...";

$calculation_results = someFunctionThatCalculatesSomething();

```


&nbsp;
&nbsp;


# Function Names

Function names should be written in `camelCase`. So the first word is lowercase, and every word after that is Capitalized. There are no underscores.


```php
//For example

function demonstrateHowToWriteFunctionNames(){...}
function beASecondDemonstration(){...}
function calculateSomething(){...}

```


&nbsp;
&nbsp;


# Indentation

Set your editor to use **spaces** instead of **tabs**. 

*2 spaces* mark an indentation.


Spaces because tab characters (these: `\t`) can cause code to be displayed in unexpected ways, 
especially when multiple people are editing the same files.


Use 2 Spaces because this is enough to visually indicate an indentation, 
but now causes code to take up less horizontal space so we can see more code 
without the statement clipping outside the editor window.


#### SETTING INDENTATION SETTINGS IN VSCODE

Open the command palette by pressing `CTRL+SHIFT+P` on the keyboard.


Type "settings" in the textbox and select "Preferences: Open User Settings".


Type "indentation" inside the "Search Settings" textbox.


Look for "Editor: Tab Size" and set that to "2".


Finally look for "Editor: Insert Spaces" and make sure the checkbox is ticked.


&nbsp;
&nbsp;


# Code Blocks

When starting a new code block, begin with the opening curly brace on the same line as the statement that opened it.

```php
//If...Else Blocks
if (condition) {

  //Do something...
}
else {

  //Do another thing...
}


//Loop blocks
foreach(condition){

  //Do this for each item...
}


```


&nbsp;
&nbsp;


# Spacing

In general, if something needs to be put on a new line, especially HTML in PHP echo statements, try to
keep them aligned with the statement that opened it.

This might be easier to understand with examples :

```php

//Maintains HTML Structure and is readable. This is also why we use spaces instead of tab characters.
echo "<h2 class='accordion-header'>".
     "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#$c-accordion-body'>".
       "$c".
     "</button>".
   "</h2>";


// Imagine trying to read something like this, but with more elements.
echo "<h2 class='accordion-header'>".
"<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#$c-accordion-body'>".
"$c".
"</button>".
"</h2>";



//This might not happen, but if function parameters are too long or there are too many.
imTryingToCallThisFunction(with, parameter1, long_parameter2, extra_long_parameter3, extra_extra_extra_long_parameter4);


//Put them on a new line and align them with whitespace.
imTryingToCallThisFunction(with, 
                           parameter1, 
                           long_parameter2, 
                           extra_long_parameter3, 
                           extra_extra_extra_long_parameter4);

```


# And Lastly, Comments

For comments, if you're starting a new block, and the first line is a comment, 
let there be a blank line between the starting block and the comment.

```php
//For example

//This...
foreach(condition){

  //Here's a comment that describes something...
  callSomeFunctionHere();


  //Describe something else...
  callAnotherFunction();
}


//Instead of this...less readable no?
foreach(condition){
  //Here's a comment that describes something...
  callSomeFunctionHere();


  //Describe something else...
  callAnotherFunction();
}

```
<?php

//All this file does is unset all the session variables and redirect the
//user back to the homepage.
session_start();
session_unset();
session_destroy();

require_once ("common/utils.php");
redirectToURL("/");
?>
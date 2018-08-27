<?php
session_start(); //Start the current session
unset($_SESSION["admin"]); //Destroy it! So we are logged out now
unset($_SESSION["password"]);
unset($_SESSION["name"]);
header("Location: http://localhost:8088"); // Move back to login.php with a logout message
?>
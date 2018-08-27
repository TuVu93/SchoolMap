<?php
session_start(); //Start the current session
$_SESSION["tour"] = "tour"; 
unset($_SESSION["IB"]);
header("Location: http://localhost:8088"); // Move back to login.php with a logout message
?>
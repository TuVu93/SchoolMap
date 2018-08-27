<?php
session_start(); //Start the current session
$_SESSION["IB"] = "IB"; 
unset($_SESSION["tour"]);
header("Location: http://localhost:8088"); // Move back to login.php with a logout message
?>
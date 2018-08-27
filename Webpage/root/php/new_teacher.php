<?php
include $_SERVER['DOCUMENT_ROOT']."/php/DB.php";

$room = $_POST['room'];   //getting the value sent through the ajax request
$fname = $_POST['fname'];  
$lname = $_POST['lname'];

$query = "INSERT INTO teacher (firstname, lastname, room_id) VALUES ('$fname', '$lname', '$room')";
mysql_query($query, $conn);
mysql_close($conn);
?>
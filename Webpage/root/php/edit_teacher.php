<?php
include $_SERVER['DOCUMENT_ROOT']."/php/DB.php";

$room = $_POST['room'];   //getting the value sent through the ajax request
$fname = $_POST['fname'];  
$lname = $_POST['lname'];
$id = $_POST['id'];

$query = "UPDATE teacher SET firstname='$fname', lastname='$lname', room_id='$room' WHERE id='$id'";
mysql_query($query, $conn);
mysql_close($conn);
?>
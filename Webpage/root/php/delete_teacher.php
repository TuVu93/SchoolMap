<?php
include $_SERVER['DOCUMENT_ROOT']."/php/DB.php";

$id = $_POST['id'];   //getting the value sent through the ajax request

echo $id;

$query = "DELETE FROM teacher WHERE id = '$id'";
mysql_query($query, $conn);
mysql_close($conn);
?>
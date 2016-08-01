<?php
include $_SERVER['DOCUMENT_ROOT']."/php/DB.php";

$room = $_POST['room'];   //getting the value sent through the ajax request
$query = "SELECT firstname, lastname FROM teacher WHERE room_id = '$room'";
$result = mysql_query( $query, $conn );
if( mysql_num_rows($result) > 0 )
	{
		echo "Room: {$room} <br> Name:<br>";
		while($row = mysql_fetch_assoc($result))
		{
			echo "{$row['firstname']} {$row['lastname']} <br>";
		}
	}
else {echo "Room: {$room} <br>"."No teacher atm!!!";}
mysql_free_result($result);
mysql_close($conn);
?>
<?php
include $_SERVER['DOCUMENT_ROOT']."/php/DB.php";

class Result {
      public $room = "";
      public $building  = "";
   }

$name = $_POST['name'];

if (strcspn($name, '0123456789') != strlen($name))
{
	$query = "SELECT building_id, floor FROM room WHERE id = '$name'";
	$isNumber = true;
}
else
{
	$query = "SELECT room_id FROM teacher WHERE (firstname = '$name') OR (lastname = '$name') OR (room_id = '$name')";
	$isNumber = false;
}
$result = mysql_query( $query, $conn );
if( mysql_num_rows($result) > 0 )
	{
		$row = mysql_fetch_assoc($result);
		if($isNumber) 
		{
			$building = $row['building_id'];
			$floor = $row['floor'];
			$room = $name;
		}
		else
		{
			$room = $row['room_id'];
			$buildingQuery = "SELECT building_id, floor FROM room WHERE id = '$room'";
			$buildingResult = mysql_query( $buildingQuery, $conn );
			$buildingRow = mysql_fetch_assoc($buildingResult);
			$building = $buildingRow['building_id'];
			$floor = $buildingRow['floor'];
			$room = $room;
		}
		
		$json = new Result();
		$json->building = $building;
		$json->floor = $floor;
		$json->room = $room;
		echo json_encode($json);
	}
else {echo 'No value';}

mysql_free_result($result);
mysql_close($conn);
?>
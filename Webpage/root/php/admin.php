<?php
session_start();                  // Start the session
if (!isset($_SESSION["admin"])) { // If session not registered
    header("location:login.php?msg="); // Redirect to login.php page
    exit;                         // Stop execution of current script
} else {
    header('Content-Type: text/html; charset=utf-8');
}
include $_SERVER['DOCUMENT_ROOT']."/php/DB.php";
?>

<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 10%; /* Location of the box */
	padding-left: 20%;
    left: 0;
    top: 0;
	width: 100%;
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.form-style-2{
    max-width: 500px;
    padding: 20px 12px 10px 20px;
    font: 13px Arial, Helvetica, sans-serif;
	background-color: #fefefe;
    border: 1px solid #888;
}
.form-style-2-heading{
    font-weight: bold;
    border-bottom: 2px solid #ddd;
    margin-bottom: 20px;
    font-size: 15px;
    padding-bottom: 3px;
}
.form-style-2 label{
    display: block;
    margin: 0px 0px 15px 0px;
}
.form-style-2 label > span{
    width: 100px;
    font-weight: bold;
    float: left;
    padding-top: 8px;
    padding-right: 5px;
}
.form-style-2 span.required{
    color:red;
}
.form-style-2 .tel-number-field{
    width: 40px;
    text-align: center;
}
.form-style-2 input.input-field{
    width: 48%;
   
}

.form-style-2 input.input-field,
.form-style-2 .textarea-field,
.form-style-2 .select-field{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    border: 1px solid #C2C2C2;
    box-shadow: 1px 1px 4px #EBEBEB;
    -moz-box-shadow: 1px 1px 4px #EBEBEB;
    -webkit-box-shadow: 1px 1px 4px #EBEBEB;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    padding: 7px;
    outline: none;
	text-transform: capitalize;
}
.form-style-2 .input-field:focus,
.form-style-2 .tel-number-field:focus,
.form-style-2 .textarea-field:focus,  
.form-style-2 .select-field:focus{
    border: 1px solid #0C0;
}
.form-style-2 .textarea-field{
    height:100px;
    width: 55%;
}
.form-style-2 input[type=submit],
.form-style-2 input[type=button]{
    border: none;
    padding: 8px 15px 8px 15px;
    background: #FF8500;
    color: #fff;
    box-shadow: 1px 1px 4px #DADADA;
    -moz-box-shadow: 1px 1px 4px #DADADA;
    -webkit-box-shadow: 1px 1px 4px #DADADA;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
}
.form-style-2 input[type=submit]:hover,
.form-style-2 input[type=button]:hover{
    background: #EA7B00;
    color: #fff;
}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Welcome To Admin Page Demonstration</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
</head>

<body>
    <a href="http://localhost:8088/"><h1>Welcome To Admin Page<br><br></h1></a>
	<h3> <?php echo $_SESSION['name'] /*Echo the username */ ?></h3>
    <p><a href="logout.php">Logout</a><br><br></p> <!-- A link for the logout page -->
    <p>Database Info</p>
		<?php
		$query = "SELECT * FROM teacher";
		$result = mysql_query($query);
		?>
	<table id = "teacher_table"> 
		<tr>
			<th>Firstname</th>
			<th>Lastname</th>
			<th>Office</th>
		</tr>
		
		<?php while($row = mysql_fetch_array($result)){   //Creates a loop to loop through results ?>
			<tr>
				<td class="fname" style="text-transform: capitalize;"><?php echo $row['firstname'] ?></td>
				<td class="lname" style="text-transform: capitalize;"><?php echo $row['lastname'] ?></td>
				<td class="room" style="text-transform: capitalize;"><?php echo $row['room_id'] ?></td>
				<td class="id" style="display: none;"><?php echo $row['id'] ?></td>
				<td><button type='submit' class="edit">Edit</button></td>
				<td><button style=" background-color: red;" type='submit' class="delete">x</button></td>
			</tr> 
		<?php } ?>
	</table> 
	
	<!-- add new teacher pop up	-->
	<div id="new_teacher_popup" class="modal">

	  <!-- Modal content -->
		<span class="close">×</span>
		<div class="form-style-2">
		<div class="form-style-2-heading"><h3 id="modalHeader">New</h3></div>
		<form action="" method="post">
		<label for="field1"><span>Firstname <span class="required">*</span></span><input id="fname" type="text" class="input-field" name="fname" value="" required /></label>
		<label for="field2"><span>Lastname <span class="required">*</span></span><input id="lname" type="text" class="input-field" name="lname" value="" required /></label>
		<label for="field4"><span>Room</span>
				
		<?php
			$query = mysql_query("SELECT * FROM room"); // Run your query
			echo '<select name="room" id="room" class="select-field">'; // Open your drop down box

			// Loop through the query results, outputing the options one by one
			while ($row = mysql_fetch_array($query)) {
			   echo '<option value="'.$row['id'].'">'.$row['id'].'</option>';
			}
			echo '</select>';// Close your drop down box ?>
		</label>

		<label><span>&nbsp;</span><input type="submit" class="save" value="Save" /></label>
		</form>
		</div>

	</div>
	<!-- End new teacher pop up -->
	
	<button id="btnNew">New</button>
	
	
<!-- JAVASCRIPT -->
<script language="javascript">
	var fromNew = false;
	var $id;
	// Get the modal
	var modal = document.getElementById('new_teacher_popup');

	// Get the button that opens the modal
	var btn = document.getElementById("btnNew");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	// When the user clicks the button, open the modal 
	btn.onclick = function() {
		document.getElementById("modalHeader").innerHTML = "New";
		modal.style.display = "block";
		fromNew = true;
	}

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
		modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
	
	$(".save").click(function() {
		var fname = document.getElementById('fname').value;
		var lname = document.getElementById('lname').value;
		var room = document.getElementById('room').value;
		
		if (fname != '' && lname != ''){
			if (confirm("Save: " + fname + " " + lname + ": " + room) == true) {
				if (fromNew){
					$.ajax({
					url: 'new_teacher.php', //Call new_teacher.php
					type: "POST",
					data: ({fname: fname, lname: lname, room: room}),
					});
				}
				else {
					$.ajax({
					url: 'edit_teacher.php', //Call edit_teacher.php
					type: "POST",
					data: ({fname: fname, lname: lname, room: room, id: $id}),
					});
				}
				window.alert("Save successful");
				location.reload(); 
			} else {
				window.alert("Canceled");
			}
		}
	});
	
	$(".edit").click(function() {
		var $firstname = $(this).closest("tr")   // Finds the closest row <tr> 
						.find(".fname")     // Gets a descendent with class="fname"
                        .text();         	// Retrieves the text within <td>	var $fname = $(this).closest("tr")  
		var $lastname = $(this).closest("tr") 
						.find(".lname")   
                        .text();        
		var $room = $(this).closest("tr") 
						.find(".room")   
                        .text();   
		$id = $(this).closest("tr")  
						.find(".id")     
                        .text();
		document.getElementById('fname').value = $firstname;
		document.getElementById('lname').value = $lastname;
		document.getElementById('room').value = $room;						
		modal.style.display = "block";
		document.getElementById("modalHeader").innerHTML = "Edit";
		fromNew = false;
	});
	
	$(".delete").click(function() {
		$id = $(this).closest("tr")   // Finds the closest row <tr> 
						.find(".id")     // Gets a descendent with class="id"
                        .text();         // Retrieves the text within <td>
		
		if (confirm("Delele?" ) == true) {
			$.ajax({
				url: 'delete_teacher.php', //Call delete_teacher.php
				type: "POST",
				data: ({id: $id}),
			});
			window.alert("Delete successful");
			location.reload(); 
		} else {
			window.alert("Canceled");
		}
	});

</script>
	
</body>
</html>
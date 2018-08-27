<?php
$username = $_POST['username']; //Set UserName
$password = $_POST['password']; //Set Password

$msg ='';
if(isset($username, $password)) {
    include $_SERVER['DOCUMENT_ROOT']."/php/DB.php"; //Initiate the MySQL connection
    // To protect MySQL injection (more detail about MySQL injection)
	$myusername = stripslashes($username);
    $mypassword = stripslashes($password);
    $myusername = mysql_real_escape_string($myusername);
    $mypassword = mysql_real_escape_string($mypassword);
    $query = "SELECT * FROM admin WHERE username='$myusername' and password=SHA('$mypassword')";
    $result = mysql_query($query, $conn);

    // If result matched $myusername and $mypassword, table row must be 1 row
    if(mysql_num_rows($result) == 1){
        // Register $myusername, $mypassword and redirect to file "admin.php"
		echo $myusername;
		session_start();
		$_SESSION["admin"]= $myusername;
        $_SESSION['password']= $mypassword;
        $_SESSION['name']= $myusername;
		var_dump($_SESSION);
        header("location:admin.php");
    }
    else {
        $msg = "Wrong Username or Password. Please retry";
		header("location:login.php?msg=$msg");
    }
}
else {
    header("location:login.php?msg=Please enter some username and password");
}
?>
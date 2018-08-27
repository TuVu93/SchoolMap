<?php
   $dbhost = 'localhost:3307';
   $dbuser = 'root';
   $dbpass = 'usbw';
   $dbname = 'test';
   $conn = mysql_connect($dbhost, $dbuser, $dbpass)
   or die('Could not connect: ' . mysql_error());
   mysql_select_db($dbname);
?>
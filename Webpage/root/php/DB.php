<?php
   $dbhost = 'localhost:3307';
   $dbuser = 'root';
   $dbpass = 'usbw';
   $conn = mysql_connect($dbhost, $dbuser, $dbpass);
   if(! $conn )
   {
	 die('Could not connect: ' . mysql_error());
   }
   mysql_select_db( 'lapinamkmap' );
?>
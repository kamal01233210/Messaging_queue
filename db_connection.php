<?php

//db_connection file for creating a database connection
$servername = "localhost";
$username = "root";
$password ="root";
$database = "kamal";

$conn = mysql_connect($servername,$username,$password);
if(!$conn) {
	die('not able to connect');
}
else {
	mysql_select_db($database) or die('unable to select db');
}


?>

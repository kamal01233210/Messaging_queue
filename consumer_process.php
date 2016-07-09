<?php

/*
* consumer process file which insert the data in the table and also writes it into a file
*/

include("db_connection.php");

if(!empty($_GET['name']) && !empty($_GET['email'] && !empty($_GET['phone']))) {
	$data = array();

	//Data to be inserted in basicInfo table
	$query = "INSERT INTO basicInfo (`name`,`email`,`phone`) values('" .$_GET['name']. "','" .$_GET['email']. "','" .$_GET['phone']."')";
		
	$result = mysql_query($query);
	
	$data[0] = $_GET['name'];
	$data[1] = $_GET['email'];
	$data[2] = $_GET['phone'];
	$msg = json_encode($data);
	//writing the encoded data in test4.txt file
	$myfile = file_put_contents('test4.txt', $msg.PHP_EOL , FILE_APPEND);
	
	if($result && $msg) {
		echo "true";
	}
	else {
		echo "false";
	}
	exit;
}
?>
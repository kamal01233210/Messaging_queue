<?php

/*
* consumer process file which insert the data in the table and also writes it into a file
*/

include("db_connection.php");

function process_data($data) {
	if(!empty($data)) {
		$output = json_decode($data);
		$name = $output[0];
		$email = $output[1];
		$phone = $output[2];
		if(!empty($name) && !empty($email) && !empty($phone)) {
			$query = "INSERT INTO basicInfo (`name`,`email`,`phone`) values('" .$name. "','" .$email. "','" .$phone."')";		
			$result = mysql_query($query);	
		}
		$myfile = file_put_contents('test4.txt', $data.PHP_EOL , FILE_APPEND);
	
		if($result && $myfile) {
			echo "true";
		}
		else {
			echo "false";
		}
		exit;
	}
}

?>
<?php

include("producer.php");

//I made an array which contains name,email,phone_no;
$data_arr = array("0"=>array($name = 'kamal',
						$email = 'sehrawatkamal@gmail.com',
						$phone = '999999999'),
						"1"=>array($name = 'raj',
						$email = 'raj@gmail.com',
						$phone = '999999999'),
						"2"=>array($name = 'john',
						$email = 'john@gmail.com',
						$phone = '999999999'),
						"3"=>array($name = 'ravi',
						$email = 'ravi@gmail.com',
						$phone = '999999999'));

//exchange and routing key for binding purpose
$exchange = 'AssignmentQueue_exchange';
$routing_key = 'AssignmentQueue_routing_key';

//calling the queue assignment function
queueAssignment($data_arr,$exchange,$routing_key);



?>
<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Logging\Logging;
use PhpAmqpLib\Message\AMQPMessage;

//enable logging is kept true here to write logs

function queueAssignment($data_arr,$exchange,$routing_key,$enable_logging=true) {
	//setting up a connection with server using username guest and password guest
	try
    {
        $conn = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest','/');
        $channel   = $conn->channel();
    } catch (Exception $e) {
        //writes logs if the flag is true
    	if ($enable_logging) {
            Logging::log_data(array('EXCEPTION' => $e->getMessage(), 'DATA' => $data_arr,'EXCHANGE' => $exchange, 'KEY' => $routing_key), 'EXCEPTION', 'FAILURE', '/tmp/rabbit_publisher.log');
        }
        return false;
    }
    $channel->confirm_select();
    try
    {
        $channel->exchange_declare($exchange, 'direct', false, false, false);
    } catch (Exception $e) {
        if ($enable_logging) {
            Logging::log_data(array('EXCEPTION' => $e, 'DATA' => $data_arr, 'EXCHANGE' => $exchange, 'KEY' => $routing_key), 'EXCEPTION', 'FAILURE', '/tmp/rabbit_publisher.log');
        }
        return false;
    }
	
    foreach ($data_arr as $data) {
    	//encoding the data for sending to queue
        $msg_body = json_encode($data);
        $channel->queue_declare('AssignmentQueue', false, false, false, false);
        
        //sending the message 
		$msg = new AMQPMessage($msg_body);
		$channel->basic_publish($msg, $exchange, $routing_key);
        //writes logs if the flag is true
        if ($enable_logging) {
            Logging::log_data(array('DATA' => $data,'EXCHANGE' => $exchange, 'KEY' => $routing_key), 'MESSAGE', 'PUBLISHING', '/tmp/rabbit_publisher.log');
        }
    }

	//Lastly, we close the channel and the connection;

	$channel->close();
	$conn->close();
    return true;
}

?>
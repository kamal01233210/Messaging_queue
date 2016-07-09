<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange = 'AssignmentQueue_exchange';
$routing_key = 'AssignmentQueue_routing_key';
$queue = 'AssignmentQueue';

//Setting up is the same as the sender; we open a connection and a channel, and declare the queue from which we're going to 
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest','/');
$channel = $connection->channel();

/*
  name: $queue
  passive: false
  durable: true // the queue will survive server restarts
  exclusive: false // the queue can be accessed in other channels
  auto_delete: false //the queue won't be deleted once the channel is closed.
 */
$channel->queue_declare($queue, false, false, false, false);

$callback = function($msg) {
	$data = json_decode($msg->body, true);
	$name = $data[0];
	$email = $data[1];
	$phone = $data[2];

	//sending the given data to a consumer process through a curl request
 	$url =  "http://localhost/kamal/amqplib/consumer_process.php?name=" .$name. "&email=" .$email. "&phone=" .$phone;
    //echo "\n".$url."\n";  //exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
    $curl_response = curl_exec($ch);

    //echo "<pre>";
                   // print_r(curl_getinfo($ch));
                    //echo curl_error($ch) . " Code ", curl_errno($ch);
    curl_close($ch);
    if($curl_response=="true")
    {
     	// $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

};

$channel->basic_consume($queue, '', false, true, false, false, $callback);
/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $ch
 * @param \PhpAmqpLib\Connection\AbstractConnection $conn
 */
function shutdown($ch, $conn) {
    $ch->close();
    $conn->close();
}

register_shutdown_function('shutdown', $ch, $conn);

// Loop as long as the channel has callbacks registered

while(count($channel->callbacks)) {
    $channel->wait();
}
?>
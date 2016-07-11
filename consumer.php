<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange = 'AssignmentQueue_exchange';
$routing_key = 'AssignmentQueue_routing_key';
$queue = 'AssignmentQueue';

include("consumer_process.php");

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
	/*
	function : process_data function written in consumer_process.php file
	input: data
	use: function is used to store the data into database and also writes it into a file
	output: true for success case and false for unsuccessfull case
	*/
	$res=process_data($msg->body);
    if($res=="true")
    {
     	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

};

$channel->basic_consume($queue, '', false, true, false, false, $callback);
/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $channel
 * @param \PhpAmqpLib\Connection\AbstractConnection $connection
 */
function shutdown($channel, $connection) {
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

// Loop as long as the channel has callbacks registered

while(count($channel->callbacks)) {
    $channel->wait();
}
?>

Installing RabbitMQ
===================
Open terminal
type `$sudo echo "deb http://www.rabbitmq.com/debian testing main" >> /etc/apt/sources.list`

After the repository is added, we will add the RabbitMQ public key to our trusted key list to avoid any warnings about unsigned packages.

type `wget https://www.rabbitmq.com/rabbitmq-signing-key-public.asc`
then  `sudo apt-key add rabbitmq-signing-key-public.asc`

Now we just need to run an update, and install the rabbitmq-server from our newly added package.

type `sudo apt-get update`
then `sudo apt-get install rabbitmq-server`

To install the plugin, use the following command:

 type `sudo rabbitmq-plugins enable rabbitmq_management`

To run it on local machine
--------------------------
`http://localhost:15672/`

Enter with user name guest and password guest

Running rabbit mq
=================

For running the required process i had saved all my files in amqlib folder which have predefined libraries in it.
Location of this folder is `/var/www/html/kamal/amqlib`

For running purpose first i will go to the current dir
type `cd /var/www/html/kamal/amqlib`

after that i will run the sendingqueue file like
type `$ php sendingqueue.php`

(this file contains all the data which is to be stored in the database and also contains exchange and routing key.queueAssignment function will process all the data through publisher)

through this file data is send to the queue.
In between i will also set the binding in rabbitmq panel by setting `AssignmentQueue_exchange` as exchange and `AssignmentQueue_routing_key` as routing key

Run consumer
------------
type `$ php consumer.php`

Once the consumer is run successfully then i  can check the database for successfull entry and the `test4.txt` file where
the data is stored successfully.

Then by pressing `ctrl+c` i can stop the consumer running.

If in case several consumer is kept running at the single time then i can kill them by typing `fg` in the command prompt and then kill each consumer.


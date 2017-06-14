<?php
use Takeoo\RedisWorker;

require "../vendor/autoload.php";

ini_set("display_errors", "1");

$configs = [
	\Takeoo\RedisWorker\Enum\Config::REDIS_CONFIG => [
		[
			"scheme" => 'tcp',
			"host" => '127.0.0.1',
			'port' => '6379',
			'password' => 'redis',
		]
	]
];

$worker = Takeoo\RedisWorker\RedisWorker::init($configs)->run();

$loop = $worker->startLoop();


$worker->publish();

$redis = new \Predis\Client();
$redis->auth("redis");

$loop = $redis->pubSubLoop();

$loop->subscribe("c-1");

/**
 * @var
 */
foreach ($loop as $message) {
	switch ($message->kind) {
		case "subscribe":
			$loop->ping("hello!");
			break;
		
		case 'message':
			if ($message->channel == 'c-1') {
				echo $message->payload;
				if ($message->payload == 'quit_loop') {
					echo "Aborting pubsub loop...", PHP_EOL;
					return;
				}
			}
	}
}


echo "Its alive!";
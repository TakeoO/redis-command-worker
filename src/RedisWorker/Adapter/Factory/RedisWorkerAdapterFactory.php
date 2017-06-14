<?php


namespace Takeoo\RedisWorker\Adapter\Factory;


use Predis\Client;
use Takeoo\RedisWorker\Adapter\RedisWorkerAdapter;
use Takeoo\RedisWorker\Enum\Config;

/**
 * Class RedisWorkerAdapterFactory
 * @package Takeoo\RedisWorker\Adapter\Factory
 */
class  RedisWorkerAdapterFactory
{
	/**
	 * Creates new adapter
	 * @param array $config
	 * @return Client
	 */
	public static function createAdapter(array $config): Client
	{
		$redis = new Client($config);
		
		if ($config[Config::REDIS_PASSWORD_KEY] ?? null)
			$redis->auth($config[Config::REDIS_PASSWORD_KEY]);
		
		RedisWorkerAdapter::setAdapter($redis);
		
		return RedisWorkerAdapter::getAdapter();
	}
}
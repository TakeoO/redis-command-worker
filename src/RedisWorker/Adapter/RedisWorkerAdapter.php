<?php
namespace Takeoo\RedisWorker\Adapter;


use Takeoo\RedisWorker\Exception\RedisAdapterNotInitializedException;

/**
 * Class RedisWorkerAdapter
 */
class RedisWorkerAdapter
{
	/**
	 * @var \Predis\Client $adapter
	 */
	private static $adapter = null;
	
	/**
	 * @return \Predis\Client
	 * @throws RedisAdapterNotInitializedException
	 */
	public static function getAdapter(): \Predis\Client
	{
		if (!self::$adapter || !self::$adapter instanceof \Predis\Client)
			throw new RedisAdapterNotInitializedException("Redis adapter not initialized!");
		
		return self::$adapter;
	}
	
	/**
	 * @param \Predis\Client $adapter
	 */
	public static function setAdapter(\Predis\Client $adapter)
	{
		self::$adapter = $adapter;
	}
}
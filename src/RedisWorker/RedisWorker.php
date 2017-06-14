<?php

namespace Takeoo\RedisWorker;

use Predis\PubSub\DispatcherLoop;

use Takeoo\RedisWorker\Enum\Config;
use Takeoo\RedisWorker\Adapter\RedisWorkerAdapter;
use Takeoo\RedisWorker\Exception\InvalidConfigException;
use Takeoo\RedisWorker\Adapter\Factory\RedisWorkerAdapterFactory;

/**
 * Worker class that is initialized for working with redis
 * Class RedisWorker
 * @package Takeoo\RedisWorker
 */
class RedisWorker
{
	
	/** @var  array $config */
	private static $config;
	
	/** @var DispatcherLoop */
	private static $loop;
	
	
	/**
	 * Starts redis worker
	 * @param array $config
	 * @return RedisWorker
	 */
	public static function init(array $config)
	{
		$slackWorker = new self();
		$slackWorker->setConfigs($config);
		return $slackWorker;
	}
	
	
	/**
	 * @param array $config
	 */
	private function setConfigs(array $config)
	{
		$this->checkConfigs($config);
		
		self::$config = $config;
	}
	
	
	/**
	 * Checks if configs are valid
	 * @param array $config
	 * @throws InvalidConfigException
	 */
	private function checkConfigs(array $config)
	{
		if (empty($config))
			throw new InvalidConfigException("Empty config sent to RedisWorker");
		
		if (!is_array($config) || !$config instanceof \Iterator)
			throw new InvalidConfigException("Configs must be an array or Iterable object");
		
		if (!isset($config[Config::REDIS_CONFIG]) || empty($config[Config::REDIS_CONFIG]))
			throw new InvalidConfigException("RedisWorker uses redis, so redis config string must be provided!");
		
	}
	
	/**
	 *  Runs worker
	 * @return RedisWorker;
	 */
	public function run()
	{
		$this->initRedis();
		
		return $this;
	}
	
	/**
	 * Creates redis adapter so it can be used for PubSub
	 */
	private function initRedis()
	{
		RedisWorkerAdapterFactory::createAdapter(self::$config[Config::REDIS_CONFIG]);
	}
	
	/**
	 * Starts pub sub loop
	 * @return null|\Predis\PubSub\Consumer|DispatcherLoop
	 * @throws InvalidConfigException
	 */
	public function startLoop()
	{
		
		// If loop already created, return loop
		if (self::$loop && self::$loop instanceof DispatcherLoop)
			return self::$loop;
		
		//start loop
		self::$loop = $loop = RedisWorkerAdapter::getAdapter()->pubSubLoop();
		
		//check if channels are in config
		//@todo: maybe not necessary to aboard whole application if no channels to subscribe ...
		if (!isset(self::$config[Config::CHANNELS_KEY]) || empty(self::$config[Config::CHANNELS_KEY]))
			throw new InvalidConfigException("No config to subscribe to, aborting....");
		
		//subscribe to channels
		foreach (self::$config[Config::CHANNELS_KEY] as $channelName)
			$loop->subscribe($channelName);
		
		return $loop;
	}
	
	/**
	 * If publish via pipeline is initialized,
	 *
	 * @param string $channelName
	 * @param $data
	 * @param bool $pipe
	 */
	public function publish(string $channelName, $data, $pipe = true)
	{
		//pipe publish via channel pipeline?
		if ($pipe)
			ChannelPipeline::pipe($channelName, $data);
		else {
			if (is_array($data))
				$data = \GuzzleHttp\json_encode($data);
			
			RedisWorkerAdapter::getAdapter()->publish($channelName, $data);
		}
	}
}
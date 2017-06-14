<?php

namespace Takeoo\RedisWorker\Commands\Interfaces;

/**
 * Interface CommandInterface
 * @package Takeoo\RedisWorker\Commands\Interfaces
 */
interface SubscribeCommandInterface extends CommandInterface
{
	/**
	 * @param string $channelName
	 * @return mixed
	 */
	public function subscribe(string $channelName);
}
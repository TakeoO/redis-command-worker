<?php

namespace Takeoo\RedisWorker\Commands\Interfaces;

/**
 * Interface CommandInterface
 * @package Takeoo\RedisWorker\Commands\Interfaces
 */
interface PublishCommandInterface extends CommandInterface
{
	/**
	 * @param string $channelName
	 * @return mixed
	 * @internal param $data
	 */
	public function publish(string $channelName);
}
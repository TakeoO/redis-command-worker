<?php

namespace Takeoo\RedisWorker\Commands;

use Takeoo\RedisWorker\Enum\Config;
use Takeoo\RedisWorker\Commands\Interfaces\PublishCommandInterface;

/**
 * Class EndLoopCommand
 * @package Takeoo\RedisWorker\Commands
 */
class EndLoopCommand extends AbstractCommand implements PublishCommandInterface
{
	/**
	 * @param string $channelName
	 * @return mixed
	 * @internal param $data
	 */
	public function publish(string $channelName)
	{
		$this->setData([Config::BREAK => true]);
	}
}
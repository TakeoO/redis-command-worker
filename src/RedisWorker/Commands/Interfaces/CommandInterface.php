<?php

namespace Takeoo\RedisWorker\Commands\Interfaces;


use Takeoo\RedisWorker\Commands\AbstractCommand;

/**
 * Interface CommandInterface
 * @package Takeoo\RedisWorker\Commands\Interfaces
 */
interface CommandInterface
{
	
	
	/**
	 * If this function return false, channel wont be subscribed or published
	 * @return AbstractCommand
	 */
	public function init(): AbstractCommand;
	
	/**
	 * Sets data published to channel to the command class
	 * @param array $data
	 * @return mixed
	 */
	public function setData($data);
	
	/**
	 * Get data published to channel to the command class
	 * @return mixed
	 */
	public function getData(): array;
}
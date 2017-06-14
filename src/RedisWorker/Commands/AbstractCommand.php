<?php

namespace Takeoo\RedisWorker\Commands;

use Takeoo\RedisWorker\Adapter\RedisWorkerAdapter;
use Takeoo\RedisWorker\Commands\Interfaces\CommandInterface;
use Takeoo\RedisWorker\Enum\Config;

/**
 * Class AbstractCommand
 * @package Takeoo\RedisWorker\Commands
 */
abstract class AbstractCommand implements CommandInterface
{
	/** @var array */
	protected $data = [];
	
	/**
	 * This is function is for initialing command
	 * @return AbstractCommand
	 */
	public function init(): AbstractCommand
	{
		if ($break = $this->getDataKey(Config::BREAK))
			$this->breakLoop();
		
		return $this;
	}
	
	/**
	 * @inheritdoc
	 */
	public function setData($data)
	{
		if (!is_array($data)) {
			if ($this->isJson($data))
				$data = \GuzzleHttp\json_decode($data);
			else
				$data = [$data]; //something fishy is going one, but we want to work with array
		}
		
		$this->data = $data;
		
		return $this;
	}
	
	/**
	 * @inheritdoc
	 */
	public function getData(): array
	{
		return $this->data;
	}
	
	/**
	 * Helper function for getting data key
	 * @param string $key
	 * @return mixed|null
	 */
	public function getDataKey(string $key)
	{
		if (!is_array($this->data))
			return null;
		
		if ($value = $this->data[$key] ?? null)
			return $value;
		
		return null;
	}
	
	
	/**
	 * Determine if string is a valid json format
	 * @Todo  : Do this more .... erm .... better ....
	 * @author: https://stackoverflow.com/a/43244302/2656311
	 * @param string $string
	 * @return bool
	 */
	protected function isJson(string $string)
	{
		$json = json_decode($string);
		return $json && $string != $json;
	}
	
	
	/**
	 * Internal publish function
	 * @todo: Add json checking
	 * @param string $channelName
	 */
	protected final function internalPublish(string $channelName)
	{
		$data = \GuzzleHttp\json_encode($this->getData());
		
		RedisWorkerAdapter::getAdapter()->publish($channelName, $data);
	}
	
	public function __call(string $name, $arguments)
	{
		if ($name === "publish")
			call_user_func([$this, "internalPublish"], $arguments);
	}
	
	/**
	 * Breaks loop
	 */
	private function breakLoop()
	{
		exit();
	}
}
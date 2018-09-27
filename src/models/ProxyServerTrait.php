<?php

namespace shintio\yii2\proxy\models;

trait ProxyServerTrait
{
	public function getIp()
	{
		return $this->ip;
	}

	public function getPort()
	{
		return $this->port;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public static function findNew()
	{
		return self::findOne(['status' => 0]);
	}

	public static function findActive()
	{
		return self::findOne(['status' => 10]);
	}

	public function setActive()
	{
		$this->status = 10;

		$this->save();
	}

	public function setUnActive()
	{
		$this->status = 5;

		$this->save();
	}

	public static function saveNew($ip, $port, $password = null, $country = null)
	{
		$proxyServer = new self([
			'ip' => $ip,
			'port' => $port,
			'password' => $password,
			'country' => $country,
		]);

		$proxyServer->save();

		return $proxyServer;
	}
}
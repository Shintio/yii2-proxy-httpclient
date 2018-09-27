<?php

namespace shintio\yii2\proxy\models;

interface ProxyServerInterface
{
	/**
	 * @return string
	 */
	public function getIp();

	/**
	 * @return string
	 */
	public function getPort();

	/**
	 * @return string
	 */
	public function getStatus();

	/**
	 * @return string
	 */
	public function getPassword();

	/**
	 * @return ProxyServerInterface
	 */
	public static function findNew();

	/**
	 * @return ProxyServerInterface
	 */
	public static function findActive();

	/**
	 * @return void
	 */
	public function setActive();

	/**
	 * @return void
	 */
	public function setUnActive();

	/**
	 * @param string $ip
	 * @param string $port
	 * @param null|string $password
	 * @param null|string $country
	 *
	 * @return ProxyServerInterface
	 */
	public static function saveNew($ip, $port, $password = null, $country = null);
}
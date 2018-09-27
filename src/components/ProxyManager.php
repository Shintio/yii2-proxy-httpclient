<?php

namespace shintio\yii2\proxy\components;

use shintio\yii2\proxy\models\ProxyServer;
use shintio\yii2\proxy\models\ProxyServerInterface;
use yii\helpers\ArrayHelper;
use yii\httpclient\Response;

/**
 * Class ProxyParser
 * @package shintio\yii2\proxy\components
 *
 * @property ProxyServerInterface $proxyServerModel
 */
class ProxyManager
{
	public $proxyServerModel = ProxyServer::class;

	public $client;

	public $checkerUrl = 'https://ipleak.net/json/';
	public $proxyLists = [
		[
			'url' => 'https://free-proxy-list.net/',
			'container' => 'table#proxylisttable',
			'ip' => 0,
			'port' => 1,
			'country' => 3,
		]
	];

	/**
	 * @return integer
	 */
	public function parseNewProxy()
	{
		$count = 0;

		foreach ($this->proxyLists as $proxyList) {
			$url = ArrayHelper::remove($proxyList, 'url');
			$container = ArrayHelper::remove($proxyList, 'container');

			$client = $this->getClient();

			$request = $client->createRequest()->setMethod('get')->setUrl($url);

			$response = $request->send();

			if ($response->isOk) {
				$document = \phpQuery::newDocumentHTML($response->content);

				$proxies = [];

				foreach ($proxyList as $key => $attribute) {
					foreach ($document->find("$container tr") as $item) {
						/** @var \DOMElement $item */
						if ($item->childNodes[$proxyList['ip']]->tagName == 'td') {
							($this->proxyServerModel)::saveNew($item->childNodes[$proxyList['ip']]->nodeValue,
								$item->childNodes[$proxyList['port']]->nodeValue,
								$item->childNodes[$proxyList['password']]->nodeValue ?? null,
								$item->childNodes[$proxyList['country']]->nodeValue ?? null);

							$count++;
						}
					}
				}
			}
		}

		return $count;
	}

	/**
	 * @param ProxyServerInterface|null $proxy
	 * @param integer $timeout
	 *
	 * @return ProxyServerInterface|boolean
	 */
	public function checkProxy($proxy = null, $timeout = 5)
	{
		if (isset($proxy)) {
			return $this->sendCheckRequest($proxy, $timeout);
		} else {
			do {
				$proxy = ($this->proxyServerModel)::findNew();

				if (isset($proxy)) {
					$proxy = $this->sendCheckRequest($proxy, $timeout);
				} else {
					return false;
				}
			} while (empty($proxy));

			return $proxy;
		}
	}

	public function getClient()
	{
		return $this->client ?? new \yii\httpclient\Client();
	}

	/**
	 * @param bool $check
	 * @param integer $timeout
	 *
	 * @return ProxyServerInterface
	 */
	public function getProxy($check = true, $timeout = 5)
	{
		$proxy = ($this->proxyServerModel)::findActive();

		return $check ? $this->checkProxy($proxy, $timeout) : $proxy;
	}

	/**
	 * @param bool $check
	 * @param integer $timeout
	 *
	 * @return string|boolean
	 */
	public function getProxyString($check = true, $timeout = 5)
	{
		$proxy = $this->getProxy($check, $timeout);

		return $proxy ? $this->formatProxyString($proxy) : false;
	}

	/**
	 * @param ProxyServerInterface $proxy
	 *
	 * @return string
	 */
	public function formatProxyString($proxy)
	{
		return 'tcp://' . $proxy->getIp() . ':' . $proxy->getPort();
	}

	/**
	 * @param ProxyServerInterface $proxy
	 * @param integer $timeout
	 *
	 * @return ProxyServerInterface|boolean
	 */
	protected function sendCheckRequest($proxy, $timeout = 5)
	{
		$client = $this->getClient();

		$request = $client->createRequest()->setMethod('get')->setOptions([
			'proxy' => $this->formatProxyString($proxy),
			'timeout' => $timeout
		])->setUrl($this->checkerUrl);

		try {
			$response = $request->send();

			if ($response->isOk) {
				$ip = $this->getCheckedIp($response);

				if ($ip == $proxy->getIp()) {
					$proxy->setActive();

					return $proxy;
				}
			}
		} catch (\Exception $exception) {

		}

		$proxy->setUnActive();

		return false;
	}

	/**
	 * @param Response $response
	 *
	 * @return string
	 */
	protected function getCheckedIp($response)
	{
		$json = $response->data;

		return $json['ip'];
	}
}
<?php

namespace shintio\yii2\proxy\components;

use shintio\yii2\proxy\models\ProxyServerInterface;
use yii\helpers\ArrayHelper;

/**
 * Class Client
 * @package shintio\yii2\proxy\components
 *
 * @property ProxyServerInterface $proxyServerClass
 * @property ProxyManager $proxyManager
 */
class Client extends \yii\httpclient\Client
{
	public $proxyServerClass;

	public $proxyManager;

	public function createRequest($rescan = true, $checkProxy = true, $checkTimeout = 5)
	{
		ArrayHelper::setValue($this->requestConfig, 'class', $this->requestConfig['class'] ?? Request::class);

		$request = parent::createRequest();

		$proxy = $this->getProxyManager()->getProxyString($checkProxy, $checkTimeout);

		if (!$proxy && $rescan) {
			$this->getProxyManager()->parseNewProxy();

			$proxy = $this->getProxyManager()->getProxyString($checkProxy, $checkTimeout);
		}

		if ($proxy) {
			$request->setOptions(['proxy' => $proxy]);
		}

		return $request;
	}

	public function getProxyManager()
	{
		return $this->proxyManager ?? new ProxyManager();
	}
}
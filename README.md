Yii2 Proxy Httpclient
=====================
Native httpclient with custom or autoparsed free proxy servers

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist shintio/yii2-proxy-httpclient "*"
```

or add

```
"shintio/yii2-proxy-httpclient": "*"
```

to the require section of your `composer.json` file.

Apply migrations by following command:
```bash
php yii migrate --migrationPath="vendor/shintio/yii2-proxy-httpclient/src/database/migrations/"
```

Usage
-----

Once the extension is installed, simply use it in your code like:

```php
use shintio\yii2\proxy\components\Client;

$client = new Client();

$request = $client->createRequest()->setMethod('get')->setUrl('https://2ip.ru/');

$response = $request->send();

echo $response->content;
die;
```

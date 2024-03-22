# Yandex Metrika Offline Conversion Client (PHP | APIv2)
[![Packagist](https://img.shields.io/badge/package-balerka/yandex--metrika--offline--conversion--php-blue.svg?style=flat-square)](https://packagist.org/packages/balerka/yandex-metrika-offline-conversion-php)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/balerka/yandex-metrika-offline-conversion-php.svg?style=flat-square)](https://packagist.org/packages/balerka/yandex-metrika-offline-conversion-php)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[PHP >=8.0](https://img.shields.io/badge/php-%3E%3D_8.0-orange.svg?style=flat-square)

### Клиент для управления офлайн-данными Яндекс.Метрики используя API

_*Внимание!*_ API находится в стадии разработки.

## Installation
Для того чтобы подключить библиотеку в свой проект, можно воспользоваться [composer](https://getcomposer.org)

```bash
composer require balerka/yandex-metrika-offline-conversion-php
```

## Usage
Пример загрузки офлайн-конверсий

При добавлении конверсии используется метод:
```
addConversion(
	$clid, 				// идентификатор посетителя сайта
	$target,  			// идентификатор цели
	$dateTime = null, 	// дата и время конверсии в формате unix timestamp (по умолчанию - текущее)
	$price = null, 		// цена (не обязательно)
	$currency = null 	// валюта (не обязательно)
);
```

```php
use Balerka\YandexMetrikaOfflineConversion\tokenSession;

$oauthToken = 'dsERGE4564GBFDG34t3GDEREBbrgbdfbg4564DG3'; // OAuth-токен
$counterId = 123456; // идентификатор счетчика
$client_id_type = Syntax::CLIENT_ID_TYPE_CLIENT; // или USER / YCLID

$metrikaOffline = new tokenSession($oauthToken);
$metrikaConversionUpload = $metrikaOffline->params($counterId, $client_id_type));
$metrikaConversionUpload->comment('Комментарий к загрузке'); // Опционально

$metrikaConversionUpload->addConversion('133591247640966458', 'GOAL1', '1481718166'); // Добавляем конверсию
$metrikaConversionUpload->addConversion('579124169844706072', 'GOAL3', '1481718116', '678.90', 'RUB'); // Добавляем ещё конверсию
/* ... и далее добавляем необходимое количество конверсий ... */

$uploadResult = $metrikaConversionUpload->send(); // Отправляем данные. $uploadResult содержит информацию о передаче, в соответствии с объектом "uploading"
```

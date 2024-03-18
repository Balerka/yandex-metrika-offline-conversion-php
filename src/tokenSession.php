<?php

namespace Balerka\YandexMetrikaOfflineConversions;

use Balerka\YandexMetrikaOfflineConversions\Http\Client;
use Balerka\YandexMetrikaOfflineConversions\Scope\Syntax;

class tokenSession
{
    const URL = 'https://api-metrika.yandex.net/management/v1';
	const VERSION = '1.0';
	private string $oAuthToken;

	public function __construct(string $token)
	{
		$this->oAuthToken = $token;
	}
	
	public function params(int $counterId, string $client_id_type = Syntax::CLIENT_ID_TYPE_YCLID): Syntax
    {
		return new Syntax($this, $counterId, $client_id_type);
	}
	
	public function getHTTPClient(): Client
    {
		return new Client($this->oAuthToken);
	}
}

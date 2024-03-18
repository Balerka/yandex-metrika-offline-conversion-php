<?php

namespace Balerka\YandexMetrikaOfflineConversions\Http;
use Balerka\YandexMetrikaOfflineConversions\tokenSession;
use Balerka\YandexMetrikaOfflineConversions\ValueObject\ConversionFile;
use Psr\Http\Message\ResponseInterface;

class Client
{
	private string $token;
	private ?string $contentType = null;
	private array $multipart;
	private string $url;
	
	public function __construct(string $token)
	{
		$this->token = $token;
	}

	public function setUrl(string $url): static
    {
		$this->url = $url;
		
		return $this;
	}
	
	public function addFile(ConversionFile $file): static
    {
		$this->contentType = 'multipart/form-data';
		$this->multipart[] = $file->getArray();
		
		return $this;
	}

    public function request($method): ResponseInterface
    {
		
		$guzzle = new \GuzzleHttp\Client([
			'headers' => [
				'Authorization' => 'OAuth ' . $this->token,
				'User-Agent'    => 'BalerkaYandexMetrikaOfflineConversions/' . tokenSession::VERSION,
				'Content-Type'  => $this->contentType
			]
		]);
		
		$optionsArray = [];
		
		if (!empty($this->multipart)) {
			$optionsArray['multipart'] = $this->multipart;
		}

        return $guzzle->$method($this->url, $optionsArray);
	}
	
}

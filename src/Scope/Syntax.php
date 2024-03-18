<?php

namespace Balerka\YandexMetrikaOfflineConversions\Scope;

use Balerka\YandexMetrikaOfflineConversions\tokenSession;
use Balerka\YandexMetrikaOfflineConversions\ValueObject\Conversion;
use Balerka\YandexMetrikaOfflineConversions\ValueObject\ConversionFile;
use Balerka\YandexMetrikaOfflineConversions\ValueObject\ConversionHeader;
use Balerka\YandexMetrikaOfflineConversions\ValueObject\ConversionsIterator;

class Syntax
{
    const CLIENT_ID_TYPE_YCLID = 'YCLID';
	const CLIENT_ID_TYPE_USER = 'USER_ID';
	const CLIENT_ID_TYPE_CLIENT = 'CLIENT_ID';
	const UPLOAD_PATH = 'upload';
    const INFO_PATH = 'uploading';
    const LIST_PATH = 'uploadings';
	
	private tokenSession $instance;
	private string $client_id_type;
	private int $counterId;
	private ConversionHeader $header;
	private ConversionsIterator $conversions;
	private string $comment;
	
	public function __construct(tokenSession $instance, int $counterId, $client_id_type = self::CLIENT_ID_TYPE_YCLID)
	{
		$this->instance = $instance;
		$this->counterId($counterId);
		$this->clientIdType($client_id_type);
		$this->header      = new ConversionHeader($this->client_id_type);
		$this->conversions = new ConversionsIterator();
	}

	public function clientIdType(string $type): static
    {
		if ($type == self::CLIENT_ID_TYPE_USER || $type == self::CLIENT_ID_TYPE_CLIENT || $type == self::CLIENT_ID_TYPE_YCLID) {
			$this->client_id_type = $type;
		}
		
		return $this;
	}

	public function counterId(int $id): static
    {
		$this->counterId = $id;
		return $this;
	}

	public function comment(string $text): static
    {
		$this->comment = $text;
		return $this;
	}
	
	public function addConversion(string $cid, string $target, string $dateTime = null, string $price = null, string $currency = null): Conversion
    {
		
		if ($price) {
			$this->header->addUsesColumn('Price');
		}
		
		if ($currency) {
			$this->header->addUsesColumn('Currency');
		}
		
		$conversion = new Conversion($cid, $target, $dateTime, $price,
			$currency);
		
		$this->conversions->append($conversion);
		
		return $conversion;
	}

    public function send(): mixed
    {
		$requestUrl = tokenSession::URL .
					  '/counter/' .
					  $this->counterId .
					  '/offline_conversions/' .
					  self::UPLOAD_PATH .
					  '?client_id_type=' .
					  $this->client_id_type;
		
		if ($this->comment) {
			$requestUrl .= '&comment=' . $this->comment;
		}
		
		$result = false;
		
		$response = $this->instance->getHTTPClient()
			->setUrl($requestUrl)
			->addFile(new ConversionFile($this->header, $this->conversions))
			->request('post');
		
		if ($response->getStatusCode() === 200) {
			$result = json_decode((string)$response->getBody());
		}
		
		return $result->uploading;
	}

    public function info(string $id): mixed
    {
        $requestUrl = tokenSession::URL .
            '/counter/' .
            $this->counterId .
            '/offline_conversions/' .
            self::INFO_PATH .
            '/' .
            $id;

        $result = false;

        try {
            $response = $this->instance->getHTTPClient()
                ->setUrl($requestUrl)
                ->request('get');
            if ($response->getStatusCode() === 200) {
                $result = json_decode((string)$response->getBody());
            }
        } catch (\Exception) {
            $result = (object)['uploading' => (object)[
                'create_time' => 0,
                'client_id_type' => 'NONE',
                'status' => 'ERROR',
                'comment' => 'none',
                'source_quantity' => 0,
                'line_quantity' => 0,
                'linked_quantity' => 0
            ]];
        }

        return $result->uploading;
    }

    public function list(): mixed
    {
        $requestUrl = tokenSession::URL .
            '/counter/' .
            $this->counterId .
            '/offline_conversions/' .
            self::LIST_PATH;

        $result = false;

        $response = $this->instance->getHTTPClient()
            ->setUrl($requestUrl)
            ->request('get');

        if ($response->getStatusCode() === 200) {
            $result = json_decode((string)$response->getBody());
        }

        return $result->uploadings;
    }
	
}

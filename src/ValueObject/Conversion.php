<?php

namespace Balerka\YandexMetrikaOfflineConversions\ValueObject;

class Conversion
{
	public string $ClientId;
	public string $Target;
	public string|int|null $DateTime;
	public ?string $Price;
	public ?string $Currency;
	
	public function __construct(string $ClientId, string $Target, $DateTime = null, $Price = null, $Currency = null)
	{
		if (!$DateTime) {
			$DateTime = time();
		}
		
		$this->ClientId = $ClientId;
		$this->Target   = $Target;
		$this->DateTime = $DateTime;
		$this->Price    = $Price;
		$this->Currency = $Currency;
	}

	public function getString(array $columns = []): string
    {
		$conversionString = $this->ClientId;
		foreach ($columns as $columnName) {
			$conversionString .= "," . $this->{$columnName};
		}
		
		return $conversionString;
	}
	
}

<?php

namespace Balerka\YandexMetrikaOfflineConversions\ValueObject;

use ArrayIterator;

class ConversionsIterator extends ArrayIterator
{
	public function __construct(array $array = [], int $flags = 0)
	{
		parent::__construct($array, $flags);
	}
	
	public function getString(array $columns = []): ?string
    {
		$conversionString = null;
		foreach ($this as $key => $conversion) {
			/** @var Conversion $conversion */
			$conversionString .= $conversion->getString($columns) . (($key == count($this) - 1) ? null : PHP_EOL);
		}
		
		return $conversionString;
	}
}

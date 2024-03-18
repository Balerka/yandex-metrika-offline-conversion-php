<?php

namespace Balerka\YandexMetrikaOfflineConversions\ValueObject;

use Balerka\YandexMetrikaOfflineConversions\Scope\Syntax;

class ConversionHeader
{
	const CLIENT_ID_TYPE_USER_COLUMN_NAME  = 'UserId';
	const CLIENT_ID_TYPE_CLIENT_COLUMN_NAME = 'ClientId';
    const CLIENT_ID_TYPE_YCL_COLUMN_NAME  = 'Yclid';
	private static array $availableColumns = ['UserId', 'ClientId', 'Yclid', 'Target', 'DateTime', 'Price', 'Currency'];

	private ?string $ClientIdType = null;
	private mixed $usesColumns;
	
	public function __construct(&$client_id_type = null)
	{
		$this->ClientIdType = &$client_id_type;
		
		$this->setDefaultUsesColumns();
	}

	public function getString(): string
    {
	    switch ($this->ClientIdType) {
            case Syntax::CLIENT_ID_TYPE_USER:
                $typeColumnName = self::CLIENT_ID_TYPE_USER_COLUMN_NAME;
                break;
            case Syntax::CLIENT_ID_TYPE_CLIENT:
                $typeColumnName = self::CLIENT_ID_TYPE_CLIENT_COLUMN_NAME;
                break;
            case Syntax::CLIENT_ID_TYPE_YCLID:
                $typeColumnName = self::CLIENT_ID_TYPE_YCL_COLUMN_NAME;
                break;
        }

		$headerString = $typeColumnName;
		foreach ($this->usesColumns as $columnName) {
			$headerString .= "," . $columnName;
		}
		
		return $headerString;
	}

	public function setDefaultUsesColumns(): void
    {
		$this->usesColumns = [];
		$this->addUsesColumn('Target');
		$this->addUsesColumn('DateTime');
	}

	public function addUsesColumn($name): void
    {
		if (in_array($name, self::$availableColumns)) {
			$this->usesColumns[] = $name;
		}
	}

	public function getUsesColumns(): mixed
    {
		return $this->usesColumns;
	}

	public function count(): int
    {
		return count($this->usesColumns) + 1;
	}
	
}

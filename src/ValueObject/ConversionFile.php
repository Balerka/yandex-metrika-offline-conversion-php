<?php

namespace Balerka\YandexMetrikaOfflineConversions\ValueObject;

class ConversionFile
{
    private string $name = 'file';
    private string $filename = 'data.csv';
    private array $headers;
    private ConversionHeader $dataHeader;
    private ConversionsIterator $dataConversions;

    /**
     * ConversionFile constructor.
     *
     * @param ConversionHeader $dataHeader
     * @param ConversionsIterator $dataConversions
     */
    public function __construct(ConversionHeader    $dataHeader,
                                ConversionsIterator $dataConversions)
    {

        $this->dataHeader = $dataHeader;
        $this->dataConversions = $dataConversions;

        $this->headers = [
            'Content-Disposition' => 'form-data; name="' . $this->name . '"; filename="' . $this->filename . '"',
            'Content-Type' => 'text/csv',
            'Content-Length' => ''
        ];
    }

    public function getArray(): array
    {

        return [
            'name' => $this->name,
            'filename' => $this->filename,
            'contents' => $this->getFileContent(),
            'headers' => $this->headers
        ];
    }

    public function getFileContent(): string
    {
        $fileContent = $this->dataHeader->getString() . PHP_EOL;
        $fileContent .= $this->dataConversions->getString($this->dataHeader->getUsesColumns());

        return $fileContent;
    }

}

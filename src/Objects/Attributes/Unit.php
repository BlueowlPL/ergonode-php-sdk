<?php

namespace Ergonode\Objects\Attributes;

class Unit extends Number
{
    protected string $unitName = '';
    protected string $unitSymbol = '';

    public function setRawData(array $rawData): void
    {
        parent::setRawData($rawData);
        $this->parseUnit();
    }

    private function parseUnit(): void
    {
        if(!empty($this->rawData[$this->type . 'Unit'])){
            $this->unitName = $this->rawData[$this->type . 'Unit']['unit']['name'];
            $this->unitSymbol = $this->rawData[$this->type . 'Unit']['unit']['symbol'];
        }
    }
}
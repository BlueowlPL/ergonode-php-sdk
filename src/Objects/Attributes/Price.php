<?php

namespace Ergonode\Objects\Attributes;

class Price extends Number
{
    protected string $currency = '';

    public function setRawData(array $rawData): void
    {
        parent::setRawData($rawData);
        $this->parseCurrency();
    }

    private function parseCurrency(): void
    {
        if(!empty($this->rawData[$this->type . 'Currency'])){
            $this->currency = $this->rawData[$this->type . 'Currency']['currency'];
        }
    }
}
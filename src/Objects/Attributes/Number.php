<?php

namespace Ergonode\Objects\Attributes;

use Ergonode\Abstracts\ErgonodeAttribute;

class Number extends ErgonodeAttribute
{
    public function parseValues(): void
    {
        $rawValues = $this->rawData[$this->type . 'ValueTranslations'] ?? [];

        if(empty($rawValues)) return;

        foreach($rawValues as $rawValue) {
            $this->translatedValues[$rawValue['language']] = $rawValue['value'];
        }
    }
}
<?php

namespace Ergonode\Objects\Attributes;

use Ergonode\Abstracts\ErgonodeAttribute;

class Select extends ErgonodeAttribute
{
    public function parseValues(): void
    {
        $rawValues = $this->rawData['SelectAttributeValueTranslations'] ?? [];

        if(empty($rawValues)) return;

        foreach($rawValues as $rawValue) {
            $this->translatedValues[$rawValue['language']] = $rawValue['translatedValue'];
        }
    }
}

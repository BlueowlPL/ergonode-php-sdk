<?php

namespace Ergonode\Objects\Attributes;

use Ergonode\Abstracts\ErgonodeAttribute;

class MultiSelect extends ErgonodeAttribute
{
    public function parseValues(): void
    {
        $rawValues = $this->rawData['MultiSelectAttributeValueTranslations'] ?? [];

        if(empty($rawValues)) return;

        foreach($rawValues as $rawValue) {
            $this->translatedValues[$rawValue['language']] = $rawValue['translatedValue'];
        }
    }
}

<?php

namespace Ergonode\Objects\Base;

use Ergonode\Abstracts\ErgonodeAttribute;
use Ergonode\Abstracts\ErgonodeObject;

class Template extends ErgonodeObject
{
    protected array $attributes = [];

    public function addAttribute(ErgonodeAttribute $attribute): void
    {
        $this->attributes[$attribute->getCode()] = $attribute;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
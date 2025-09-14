<?php

namespace Ergonode\Objects\Base;

use Ergonode\Abstracts\ErgonodeObject;

class Language extends ErgonodeObject
{
    protected string $name;

    public function __construct(string $code)
    {
        parent::__construct($code);

        $this->name = locale_get_display_name($this->code, 'en');
    }
}
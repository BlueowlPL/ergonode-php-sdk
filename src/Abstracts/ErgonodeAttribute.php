<?php

namespace Ergonode\Abstracts;

use Ergonode\Objects\Attributes\Date;
use Ergonode\Objects\Attributes\File;
use Ergonode\Objects\Attributes\Gallery;
use Ergonode\Objects\Attributes\Image;
use Ergonode\Objects\Attributes\MultiSelect;
use Ergonode\Objects\Attributes\Number;
use Ergonode\Objects\Attributes\Price;
use Ergonode\Objects\Attributes\ProductRelation;
use Ergonode\Objects\Attributes\Select;
use Ergonode\Objects\Attributes\Text;
use Ergonode\Objects\Attributes\Textarea;
use Ergonode\Objects\Attributes\Unit;

abstract class ErgonodeAttribute
{
    protected string $type;
    protected string $code;
    protected array $translatedNames = [];
    protected array $translatedValues = [];

    protected array $rawData = [];

    public function __construct(string $code, string $type = 'TextAttribute')
    {
        $this->type = $type;
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public static function handleType(string $type): ?string
    {
        return match ($type) {
            'TextAttribute' => Text::class,
            'TextareaAttribute' => Textarea::class,
            'DateAttribute' => Date::class,
            'UnitAttribute' => Unit::class,
            'PriceAttribute' => Price::class,
            'NumericAttribute' => Number::class,
            'ProductRelationAttribute' => ProductRelation::class,
            'FileAttribute' => File::class,
            'GalleryAttribute' => Gallery::class,
            'ImageAttribute' => Image::class,
            'MultiSelectAttribute' => MultiSelect::class,
            'SelectAttribute' => Select::class,
            default => null,
        };
    }

    public function setRawData(array $rawData): void
    {
        $this->rawData = $rawData;
        if(empty($this->rawData)) return;
        $this->parseNames();
        $this->parseValues();
    }

    private function parseNames(): void
    {
        if(empty($this->rawData)) {
            return;
        }

        $attributeData = $this->rawData['attribute'] ?? $this->rawData;
        foreach ($attributeData['name'] as $name) {
            $this->translatedNames[$name['language']] = $name['value'];
        }
    }

    public function parseValues(): void
    {
        $rawValues = $this->rawData[$this->type . 'ValueTranslations'] ?? [];

        if(empty($rawValues)) return;

        foreach($rawValues as $rawValue) {
            $this->translatedValues[$rawValue['language']] = $rawValue['value'];
        }
    }
}
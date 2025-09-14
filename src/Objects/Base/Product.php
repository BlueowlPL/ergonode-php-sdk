<?php

namespace Ergonode\Objects\Base;

use Ergonode\Abstracts\ErgonodeAttribute;
use Ergonode\Abstracts\ErgonodeObject;

class Product extends ErgonodeObject
{
    protected string $createdAt = '';
    protected string $editedAt = '';
    protected string $templateCode = '';
    protected string $type = '';
    protected array $attributes = [];

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = strtotime(date($createdAt));
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setEditedAt(string $editedAt): void
    {
        $this->editedAt = strtotime(date($editedAt));
    }

    public function getEditedAt(): string
    {
        return $this->editedAt;
    }

    public function setTemplateCode(string $templateCode): void
    {
        $this->templateCode = $templateCode;
    }

    public function getTemplateCode(): string
    {
        return $this->templateCode;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function addAttribute(ErgonodeAttribute $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
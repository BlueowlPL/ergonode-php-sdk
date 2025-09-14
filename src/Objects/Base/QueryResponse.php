<?php

namespace Ergonode\Objects\Base;

use Ergonode\Abstracts\ErgonodeObject;

class QueryResponse
{
    protected bool $hasNext = false;
    protected int $total = 0;
    protected string $cursor = '';
    protected array $data = [];
    protected \Exception $error;
    protected bool $isError = false;

    public function __construct()
    {
        $this->hasNext = false;
        $this->total = 0;
        $this->cursor = '';
        $this->data = [];
        $this->isError = false;
    }

    public function setHasNext(bool $hasNext): static
    {
        $this->hasNext = $hasNext;
        return $this;
    }

    public function hasNext(): bool
    {
        return $this->hasNext;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setCursor(string $cursor): void
    {
        $this->cursor = $cursor;
    }

    public function getCursor(): string
    {
        return $this->cursor;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function isError(): bool
    {
        return $this->isError;
    }

    public function getError(): ?\Exception
    {
        return $this->isError ? $this->error : null;
    }

    public function setError(\Exception $error): static
    {
        $this->error = $error;
        $this->isError = true;
        return $this;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function addData(ErgonodeObject $ergonodeObject): static
    {
        $this->data[] = $ergonodeObject;
        return $this;
    }
}
<?php

namespace Ergonode\Abstracts;

use Ergonode\GraphQLClient;
use Ergonode\GraphQLQueryLoader;
use Ergonode\Objects\Base\QueryResponse;

abstract class QueryBuilder
{
    protected GraphQLQueryLoader $loader;
    protected GraphQLClient $client;

    protected int $perPage = 50;
    protected string $cursor = '';
    protected bool $hasNext = true;

    protected string $code;
    protected bool $isSingle = false;

    public function __construct(GraphQLQueryLoader $loader, GraphQLClient $client)
    {
        $this->loader = $loader;
        $this->client = $client;
    }

    public function perPage(int $limit): static
    {
        $this->perPage = max(1, $limit);
        return $this;
    }

    public function single(string $code): static
    {
        $this->isSingle = true;
        $this->code = $code;
        return $this;
    }

    public function get(): QueryResponse
    {
        if(!$this->hasNext) return (new QueryResponse())->setError(new \Exception('No more data'));

        $query = $this->getQuery();
        if(empty($query)) return (new QueryResponse())->setError(new \Exception('Query is empty'));

        $variables = $this->isSingle ? [
            'code' => $this->code
        ] : [
            'first' => $this->perPage,
            'after' => $this->cursor
        ];

        $response = $this->client->query($query, $variables);

        if(!empty($response['errors'])){
            return (new QueryResponse())->setError(new \Exception(print_r($response['errors'], true)));
        }

        $rootKey = array_key_first($response['data']);
        $this->getHeaders($response['data'][$rootKey]);

        $extractedData = $this->isSingle ? $this->extractSingleData($response['data'][$rootKey]) : $this->extractManyData($response['data'][$rootKey]);
        $extractedData->setHasNext($this->hasNext);
        $extractedData->setCursor($this->cursor);

        if($extractedData->isError()){
            $this->hasNext = false;
            $this->cursor = '';
        }

        return $extractedData;
    }

    public function getQuery(): string
    {
        $replacements = $this->withFragments();
        $queryName = $this->isSingle ? $this->getSingle() : $this->getAll();
        if(empty($queryName)) return '';

        return $this->loader->load($queryName, $replacements);
    }

    private function getHeaders(array $response): void
    {
        if(!empty($response['pageInfo'])){
            $this->hasNext = $response['pageInfo']['hasNextPage'] ?? false;
            $this->cursor = $response['pageInfo']['endCursor'] ?? '';
        } else {
            $this->hasNext = false;
            $this->cursor = '';
        }
    }

    protected function withFragments(): array
    {
        return [];
    }
    abstract protected function getAll(): string;
    abstract protected function getSingle(): string;
    abstract protected function extractManyData(array $rawData): QueryResponse;
    abstract protected function extractSingleData(array $rawData): QueryResponse;
}
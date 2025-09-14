<?php

namespace Ergonode;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GraphQLClient
{
    private Client $http;

    public function __construct(string $baseUri, string $token, string $authHeaderName = 'X-API-KEY', array $options = [])
    {
        $this->http = new Client(array_merge([
            'base_uri' => rtrim($baseUri, '/'),
            'headers' => [
                $authHeaderName => $token,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30.0
        ], $options));
    }

    public function query(string $query, array $variables = []): array
    {
        try{
            $response = $this->http->post('/api/graphql/', [
                'body' => json_encode([
                    'query' => $query,
                    'variables' => $variables
                ])
            ]);
        } catch (GuzzleException $exception){
            throw new \RuntimeException($exception->getMessage());
        }

        $body = (string)$response->getBody();
        $decoded = json_decode($body, true);
        if(json_last_error() !== JSON_ERROR_NONE){
            throw new \JsonException('Failed to decode response body');
        }

        return $decoded;
    }
}
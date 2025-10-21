<?php

namespace Ergonode;

use Ergonode\Builders\LanguageBuilder;
use Ergonode\Builders\ProductBuilder;
use Ergonode\Builders\ProductBaseBuilder;
use Ergonode\Builders\TemplateBuilder;

class ErgonodeClient
{
    private GraphQLClient $client;
    private GraphQLQueryLoader $loader;

    public function __construct(string $baseUrl, string $token, string $authHeaderName = 'X-API-KEY')
    {
        $this->client = new GraphQLClient($baseUrl, $token, $authHeaderName);
        $this->loader = new GraphQLQueryLoader();
    }

    public function languages(): LanguageBuilder
    {
        return new LanguageBuilder($this->loader, $this->client);
    }
    public function templates(): TemplateBuilder{
        return new TemplateBuilder($this->loader, $this->client);
    }
    public function products(): ProductBuilder{
        return new ProductBuilder($this->loader, $this->client);
    }
    public function productsBase(): ProductBaseBuilder{ 
        return new ProductBaseBuilder($this->loader, $this->client); 
    }
}

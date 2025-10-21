<?php

namespace Ergonode\Builders;

use Ergonode\Abstracts\ErgonodeAttribute;
use Ergonode\Abstracts\QueryBuilder;
use Ergonode\Objects\Base\Product;
use Ergonode\Objects\Base\QueryResponse;

class ProductBaseBuilder extends QueryBuilder
{
    protected function getAll(): string
    {
        return 'products';
    }

    protected function getSingle(): string
    {
        return 'product';
    }

    protected function extractManyData(array $rawData): QueryResponse
    {
        $extractedData = new QueryResponse();

        if(empty($rawData['edges'])){
            $extractedData->setError(new \Exception('No products found'));
            return $extractedData;
        }
        $extractedData->setTotal((int)$rawData['totalCount']);

        foreach($rawData['edges'] as $rawProducts){
            $extractedData->addData($this->parseProduct($rawProducts['node']));
        }
        return $extractedData;
    }

    protected function extractSingleData(array $rawData): QueryResponse
    {
        $extractedData = new QueryResponse();

        if(empty($rawData)){
            $extractedData->setError(new \Exception('Product not found'));
            return $extractedData;
        }
        $extractedData->setTotal(1);
        $extractedData->addData($this->parseProduct($rawData));
        return $extractedData;
    }

    private function parseProduct(array $rawProduct): Product
    {
        $product = new Product($rawProduct['sku']);
        $product->setCreatedAt($rawProduct['createdAt']);
        $product->setEditedAt($rawProduct['editedAt']);
        $product->setTemplateCode($rawProduct['template']['code']);
        $product->setType($rawProduct['__typename']);

        if(empty($rawProduct['attributeList']['edges'])){
            return $product;
        }

        foreach($rawProduct['attributeList']['edges'] as $rawAttribute){
            $class = ErgonodeAttribute::handleType($rawAttribute['node']['attribute']['__typename']);
            if($class === null) continue;

            $attribute = new $class($rawAttribute['node']['attribute']['code'], $rawAttribute['node']['attribute']['__typename']);
            $attribute->setRawData($rawAttribute['node']);
            $product->addAttribute($attribute);
        }

        return $product;
    }

    protected function withFragments(): array
    {
        return [
            'fragment.productBase',
        ];
    }
}

<?php

namespace Ergonode\Builders;

use Ergonode\Abstracts\ErgonodeAttribute;
use Ergonode\Abstracts\QueryBuilder;
use Ergonode\Objects\Base\QueryResponse;
use Ergonode\Objects\Base\Template;

class TemplateBuilder extends QueryBuilder
{

    protected function getAll(): string
    {
        return 'templates';
    }

    protected function getSingle(): string
    {
        return '';
    }

    protected function extractManyData(array $rawData): QueryResponse
    {
        $extractedData = new QueryResponse();
        $extractedData->setTotal((int)$rawData['totalCount']);

        if(empty($rawData['edges'])) {
            $extractedData->setError(new \Exception('No templates found'));
            return $extractedData;
        }

        foreach ($rawData['edges'] as $rawTemplate) {
            $template = new Template($rawTemplate['node']['code']);

            if(empty($rawTemplate['node']['attributeList']['edges'])){
                $extractedData->addData($template);
                continue;
            }

            foreach ($rawTemplate['node']['attributeList']['edges'] as $rawAttribute) {
                $class = ErgonodeAttribute::handleType($rawAttribute['node']['__typename']);
                if($class === null) continue;

                $attribute = new $class($rawAttribute['node']['code'], $rawAttribute['node']['__typename']);
                $attribute->setRawData($rawAttribute['node']);
                $template->addAttribute($attribute);
            }

            $extractedData->addData($template);
        }
        return $extractedData;
    }

    protected function extractSingleData(array $rawData): QueryResponse
    {
        return $this->extractManyData($rawData);
    }
}
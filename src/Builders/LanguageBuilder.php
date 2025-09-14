<?php

namespace Ergonode\Builders;

use Ergonode\Abstracts\QueryBuilder;
use Ergonode\Objects\Base\Language;
use Ergonode\Objects\Base\QueryResponse;

class LanguageBuilder extends QueryBuilder
{
    protected function getAll(): string
    {
        return 'languages';
    }

    protected function getSingle(): string
    {
        return '';
    }

    protected function extractManyData(array $rawData): QueryResponse
    {
        $extractedData = new QueryResponse();

        $extractedData->setTotal((int)$rawData['totalCount']);
        if(empty($rawData['edges'])){
            $extractedData->setError(new \Exception('No languages found'));
            return $extractedData;
        }
        foreach($rawData['edges'] as $language){
            $extractedData->addData(new Language($language['node']));
        }
        return $extractedData;
    }

    protected function extractSingleData(array $rawData): QueryResponse
    {
        return $this->extractManyData($rawData);
    }
}
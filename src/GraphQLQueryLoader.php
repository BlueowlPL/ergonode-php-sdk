<?php

namespace Ergonode;

class GraphQLQueryLoader
{
    private array $cache = [];
    private array $queries = [
        'languages' => 'listLanguages.graphql',
        'templates' => 'listTemplates.graphql',
        'products' => 'listProducts.graphql',
        'product' => 'singleProduct.graphql',

        'fragment.product' => 'fragments/product.graphql',
        'fragment.attribute' => 'fragments/attributeValue.graphql',
        'fragment.multimedia' => 'fragments/multimedia.graphql',
        'fragment.options' => 'fragments/optionTranslations.graphql',
    ];

    public function load(string $templateName, array $replacements = []): string
    {
        $cacheKey = $templateName . '::' . md5(json_encode($replacements));
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $filePath = $this->findFile($this->queries[$templateName]);
        if ($filePath === null) {
            throw new \InvalidArgumentException('Query file not found: ' . $filePath);
        }

        $content = file_get_contents($filePath);
        if(empty($content)){
            throw new \InvalidArgumentException('Query file is empty: ' . $filePath);
        }

        $resolved = $this->resolveFragments($content, $replacements);

        $this->cache[$cacheKey] = $resolved;
        return $resolved;
    }

    private function findFile(string $relative): ?string
    {
        $filePath = __DIR__ . '/Queries/' . $relative;
        if(file_exists($filePath)){
            return $filePath;
        }

        return null;
    }

    private function resolveFragments(string $query, array $replacements = []): string
    {
        if(empty($replacements) || !str_contains($query, '{FRAGMENTS}')) {
            return str_replace('{FRAGMENTS}', '', $query);
        }

        $fragments = '';
        foreach ($replacements as $fragment) {
            $fragmentFile = $this->findFile($this->queries[$fragment]);
            if($fragmentFile === null){
                throw new \InvalidArgumentException('Fragment file not found: ' . $fragmentFile);
            }
            $content = file_get_contents($fragmentFile);
            if(empty($content)){
                throw new \InvalidArgumentException('Fragment file is empty: ' . $fragmentFile);
            }
            $fragments .= $content;
        }

        return str_replace('{FRAGMENTS}', $fragments, $query);
    }
}
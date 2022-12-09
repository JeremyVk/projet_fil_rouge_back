<?php
// api/src/Filter/RegexpFilter.php

namespace App\Filters;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;

final class AllBookSearchFilter extends AbstractFilter
{

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $querys = explode(" ", $value);

        foreach($querys as $query) {
            $queryBuilder  
            ->orWhere("o.title LIKE :value")
            ->orWhere("o.editor LIKE :value")
            ->orWhere("o.resume LIKE :value")
            ->setParameter('value', "%" . $query . "%");  
        } 

        // $queryBuilder
        // ->orderBy("o.stock", "DESC");
}

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];

        return $description;
    }
}
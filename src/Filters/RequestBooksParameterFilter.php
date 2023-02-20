<?php
// api/src/Filter/RegexpFilter.php

namespace App\Filters;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

final class RequestBooksParameterFilter extends AbstractFilter
{

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ($property !== "id") {
            return;
        }
        $ids = explode(",", $value);

        $queryBuilder
            ->andWhere("o.id IN (:ids)")
            ->setParameter("ids", $ids)
        ;
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
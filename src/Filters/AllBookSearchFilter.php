<?php
// api/src/Filter/RegexpFilter.php

namespace App\Filters;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Articles\Book\Book;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;
use App\Entity\Variants\BookVariant;
use Doctrine\ORM\EntityManagerInterface;

final class AllBookSearchFilter extends AbstractFilter
{

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ($property === "formats") {
            $formats = explode(',', $value);

            $queryBuilder
                ->innerJoin("o.variants", "variants")
                ->join("variants.format", "f")
                ->andWhere("f.name in (:formats)")
                ->setParameter("formats", $formats)
            ;
        }

        if ($property === "query") {
            $querys = explode(" ", $value);

            foreach($querys as $query) {
                $queryBuilder
                    ->andWhere("o.title LIKE :value")
                    ->setParameter('value', "%" . $query . "%");
            } 
        } 
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
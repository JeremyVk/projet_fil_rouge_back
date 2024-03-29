<?php

namespace App\Repository;

use App\Entity\Abstract\BaseVariant\BaseVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Variants\BookVariant;

/**
 * @extends ServiceEntityRepository<BaseVariant>
 *
 * @method BaseVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseVariant[]    findAll()
 * @method BaseVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseVariant::class);
    }

    public function save(BaseVariant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BaseVariant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

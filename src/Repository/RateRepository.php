<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rate>
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function deleteRatesForBaseCurrency(Currency $baseCurrency): void
    {
        $this->createQueryBuilder('r')
            ->delete(Rate::class, 'r')
            ->where('r.baseCurrency = :baseCurrency')
            ->setParameter('baseCurrency', $baseCurrency)
            ->getQuery()
            ->execute();
    }

    public function persist(Rate $rate): void
    {
        $this->getEntityManager()->persist($rate);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function beginTransaction(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function rollback(): void
    {
        $this->getEntityManager()->rollback();
    }

    public function commit(): void
    {
        $this->getEntityManager()->commit();
    }

    public function wrapInTransaction(callable $func): void
    {
        $this->getEntityManager()->wrapInTransaction($func);
    }
}

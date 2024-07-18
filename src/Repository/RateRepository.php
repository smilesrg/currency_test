<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\Rate;
use App\Service\Currency\ValueObject\CurrencyCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

    /**
     * @throws NonUniqueResultException If the query result is not unique.
     * @throws NoResultException        If the query returned no result.
     */
    public function findRate(CurrencyCode $baseCurrency, CurrencyCode $targetCurrency): Rate
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.baseCurrency = :baseCurrency')
            ->andWhere('r.targetCurrency = :targetCurrency')
            ->setParameter('baseCurrency', $baseCurrency)
            ->setParameter('targetCurrency', $targetCurrency)
            ->getQuery()
            ->getSingleResult();
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

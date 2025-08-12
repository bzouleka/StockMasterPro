<?php

namespace App\Repository;

use App\Entity\StockMovement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockMovement>
 *
 * @method StockMovement|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockMovement|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockMovement[]    findAll()
 * @method StockMovement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockMovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockMovement::class);
    }

    public function save(StockMovement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StockMovement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Obtenir les mouvements de stock des derniers mois
     */
    public function getMonthlyMovements(int $months = 6): array
    {
        $qb = $this->createQueryBuilder('sm')
            ->select('MONTH(sm.createdAt) as month, YEAR(sm.createdAt) as year, sm.type, SUM(sm.quantity) as total')
            ->where('sm.createdAt >= :startDate')
            ->setParameter('startDate', new \DateTime("-{$months} months"))
            ->groupBy('month, year, sm.type')
            ->orderBy('year', 'ASC')
            ->addOrderBy('month', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Obtenir les mouvements récents
     */
    public function getRecentMovements(int $limit = 10): array
    {
        return $this->createQueryBuilder('sm')
            ->leftJoin('sm.product', 'p')
            ->leftJoin('sm.user', 'u')
            ->select('sm, p, u')
            ->orderBy('sm.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Compter les produits avec un stock faible
     */
    public function countLowStockProducts(): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.currentStock <= p.minStockLevel')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Calculer la valeur totale de l'inventaire
     */
    public function getTotalInventoryValue(): float
    {
        $qb = $this->createQueryBuilder('p')
            ->select('SUM(p.currentStock * p.costPrice)')
            ->where('p.isActive = :active')
            ->setParameter('active', true);

        $result = $qb->getQuery()->getSingleScalarResult();
        
        return $result ?? 0.0;
    }

    /**
     * Trouver les produits avec un stock faible
     */
    public function findLowStockProducts(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.currentStock <= p.minStockLevel')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('p.currentStock', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les produits les plus vendus
     */
    public function findTopProducts(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->select('p, SUM(sm.quantity) as totalSold')
            ->leftJoin('p.stockMovements', 'sm')
            ->where('p.isActive = :active')
            ->andWhere('sm.type = :outType')
            ->setParameter('active', true)
            ->setParameter('outType', 'OUT')
            ->groupBy('p.id')
            ->orderBy('totalSold', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les produits avec filtres
     */
    public function findByFilters(array $criteria, int $limit = 20, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.supplier', 's')
            ->where('p.isActive = :active')
            ->setParameter('active', true);

        if (isset($criteria['search']) && $criteria['search']) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('p.name', ':search'),
                    $qb->expr()->like('p.sku', ':search'),
                    $qb->expr()->like('p.barcode', ':search'),
                    $qb->expr()->like('p.description', ':search')
                )
            )
            ->setParameter('search', '%' . $criteria['search'] . '%');
        }

        if (isset($criteria['category']) && $criteria['category']) {
            $qb->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $criteria['category']);
        }

        if (isset($criteria['stockStatus']) && $criteria['stockStatus']) {
            switch ($criteria['stockStatus']) {
                case 'LOW':
                    $qb->andWhere('p.currentStock <= p.minStockLevel');
                    break;
                case 'HIGH':
                    $qb->andWhere('p.currentStock >= p.maxStockLevel');
                    break;
                case 'NORMAL':
                    $qb->andWhere('p.currentStock > p.minStockLevel AND p.currentStock < p.maxStockLevel');
                    break;
            }
        }

        return $qb->orderBy('p.name', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * Compter les produits avec filtres
     */
    public function countByFilters(array $criteria): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.supplier', 's')
            ->where('p.isActive = :active')
            ->setParameter('active', true);

        if (isset($criteria['search']) && $criteria['search']) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('p.name', ':search'),
                    $qb->expr()->like('p.sku', ':search'),
                    $qb->expr()->like('p.barcode', ':search'),
                    $qb->expr()->like('p.description', ':search')
                )
            )
            ->setParameter('search', '%' . $criteria['search'] . '%');
        }

        if (isset($criteria['category']) && $criteria['category']) {
            $qb->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $criteria['category']);
        }

        if (isset($criteria['stockStatus']) && $criteria['stockStatus']) {
            switch ($criteria['stockStatus']) {
                case 'LOW':
                    $qb->andWhere('p.currentStock <= p.minStockLevel');
                    break;
                case 'HIGH':
                    $qb->andWhere('p.currentStock >= p.maxStockLevel');
                    break;
                case 'NORMAL':
                    $qb->andWhere('p.currentStock > p.minStockLevel AND p.currentStock < p.maxStockLevel');
                    break;
            }
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

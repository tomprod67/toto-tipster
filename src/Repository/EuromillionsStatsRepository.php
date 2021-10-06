<?php

namespace App\Repository;

use App\Entity\EuromillionsStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EuromillionsStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method EuromillionsStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method EuromillionsStats[]    findAll()
 * @method EuromillionsStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EuromillionsStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EuromillionsStats::class);
    }

    public function findSince()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.year >= :year')
            ->andWhere('e.month != :month')
            ->setParameter('year', 2016)
            ->setParameter('month', 'ALL')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return EuromillionsStat[] Returns an array of EuromillionsStat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EuromillionsStat
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\EuromillionsTirages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EuromillionsTirages|null find($id, $lockMode = null, $lockVersion = null)
 * @method EuromillionsTirages|null findOneBy(array $criteria, array $orderBy = null)
 * @method EuromillionsTirages[]    findAll()
 * @method EuromillionsTirages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EuromillionsTiragesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EuromillionsTirages::class);
    }


    public function findBySuperiorId($id)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id > :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

}
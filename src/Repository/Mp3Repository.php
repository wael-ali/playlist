<?php

namespace App\Repository;

use App\Entity\Mp3;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mp3|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mp3|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mp3[]    findAll()
 * @method Mp3[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Mp3Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mp3::class);
    }

    // /**
    //  * @return Mp3[] Returns an array of Mp3 objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mp3
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Coloriage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Coloriage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coloriage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coloriage[]    findAll()
 * @method Coloriage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColoriageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coloriage::class);
    }

    // /**
    //  * @return Coloriage[] Returns an array of Coloriage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Coloriage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\AvisJeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AvisJeu|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisJeu|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisJeu[]    findAll()
 * @method AvisJeu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisJeuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisJeu::class);
    }

    // /**
    //  * @return AvisJeu[] Returns an array of AvisJeu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AvisJeu
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

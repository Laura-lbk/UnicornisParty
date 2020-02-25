<?php

namespace App\Repository;

use App\Entity\Fanart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Fanart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fanart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fanart[]    findAll()
 * @method Fanart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FanartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fanart::class);
    }

    public function findImage(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT image FROM fanart p
            ORDER BY p.date ASC
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }


    // /**
    //  * @return Fanart[] Returns an array of Fanart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fanart
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

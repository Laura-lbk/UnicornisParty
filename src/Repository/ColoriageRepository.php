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

    public function findImage(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT image FROM coloriage p
            ORDER BY p.id ASC
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
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

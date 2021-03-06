<?php

namespace App\Repository;

use App\Entity\ArticleNews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ArticleNews|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleNews|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleNews[]    findAll()
 * @method ArticleNews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleNewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleNews::class);
    }

    public function findImage(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT image FROM article_news p
            ORDER BY p.id ASC
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    // /**
    //  * @return ArticleNews[] Returns an array of ArticleNews objects
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
    public function findOneBySomeField($value): ?ArticleNews
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

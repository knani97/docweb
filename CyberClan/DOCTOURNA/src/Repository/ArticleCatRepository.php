<?php

namespace App\Repository;

use App\Entity\ArticleCat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleCat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleCat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleCat[]    findAll()
 * @method ArticleCat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleCatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleCat::class);
    }

    public function maxCat($date,$mois):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='SELECT  COUNT(a.id_cat_id) AS nbr,cat.categorie,cat.image 
FROM article_cat cat 
INNER JOIN article a ON a.id_cat_id = cat.id
WHERE(Month(a.date_ajout)='.$mois.' AND Year(a.date_ajout)='.$date.' ) AND a.etat_Ajout=1
GROUP BY a.id_cat_id 
ORDER BY nbr DESC
LIMIT 1
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }
    public function CountCat($date,$mois):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='SELECT  COUNT(a.id_cat_id) AS nbr,cat.categorie,cat.image 
FROM article_cat cat 
INNER JOIN article a ON a.id_cat_id = cat.id
WHERE(Month(a.date_ajout)='.$mois.' AND Year(a.date_ajout)='.$date.' ) AND a.etat_Ajout=1
GROUP BY a.id_cat_id 
ORDER BY nbr DESC
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }


    // /**
    //  * @return ArticleCat[] Returns an array of ArticleCat objects
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
    public function findOneBySomeField($value): ?ArticleCat
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

<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }



    public function articlesearch():array{
        $conn=$this->getEntityManager()->getConnection();
        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN users u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id;
        ';
        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }
    public function articlesearchById($id):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN users u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.id='.$id.';
        ';
        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }
    public function AutrearticlesearchById($id):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN users u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where (a.id!='.$id.') AND (a.id_user=(SELECT id_user FROM article WHERE id='.$id.')) 
        LIMIT 2;
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }
    public function RecommArticle($id):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN users u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.id_cat_id=(SELECT id_cat_id FROM article WHERE id='.$id.') AND etat_ajout=1
        LIMIT 10
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }
    public function mesArticle($idUser):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN users u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.id_user='.$idUser.'
        order by date_ajout desc
        LIMIT 9
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function ValiderArtile():array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN users u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.etat_ajout=0
        order by date_ajout desc
        LIMIT 10
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }
    public function notif():array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN users u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.etat_ajout=1
        order by date_ajout desc
        LIMIT 10
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function recomm($id):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN users u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.etat_ajout=1 AND a.id_cat_id=(SELECT MAX(`id_cat_id`) FROM article 
GROUP BY id_user
HAVING id_user='.$id.') AND a.id_user!='.$id.'
        LIMIT 3
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function reagit($id):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='select DISTINCT (SELECT COUNT(id) AS dislikereact FROM reagit WHERE type_react=0),
(SELECT COUNT(id) AS likereact FROM reagit WHERE type_react=1)
from reagit
WHERE id_art_id='.$id.'
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }
    public function trends():array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='SELECT COUNT(type_react) FROM `reagit` GROUP BY id_art_id ORDER BY COUNT(type_react) DESC LIMIT 1

        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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
    public function findOneBySomeField($value): ?Article
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

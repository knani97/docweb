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
        INNER JOIN user u ON u.id = a.id_user
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
        INNER JOIN user u ON u.id = a.id_user
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
        INNER JOIN user u ON u.id = a.id_user
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
        INNER JOIN user u ON u.id = a.id_user
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
        INNER JOIN user u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.id_user='.$idUser.'
        order by date_ajout desc
        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function ValiderArtile():array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie,COUNT(a.id) AS countnotif
        FROM article a 
        INNER JOIN user u ON u.id = a.id_user 
        INNER JOIN article_cat c ON a.id_cat_id = c.id 
        where a.etat_ajout=0 
        GROUP by a.id 
        order by date_ajout desc

        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }
    public function notif($idUser):array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN user u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.etat_ajout=1 AND a.id_user='.$idUser.'
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
        INNER JOIN user u ON u.id = a.id_user
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

    public function trends():array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='SELECT COUNT(type_react) FROM `reagit` GROUP BY id_art_id ORDER BY COUNT(type_react) DESC LIMIT 1

        ';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public  function reagitLike():array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='SELECT *,COUNT(id) as liked 
from reagit 
WHERE type_react=1
GROUP BY id_art_id';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public  function reagitDislike():array{
        $conn=$this->getEntityManager()->getConnection();

        $sql='SELECT *,COUNT(id) as dislike 
from reagit 
WHERE type_react=0
GROUP BY id_art_id';

        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }


    public function articlePrefere($idUser):array{
        $conn=$this->getEntityManager()->getConnection();
        $sql='
SELECT DISTINCT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN user u ON u.id = a.id_user 
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        INNER JOIN reagit r on r.id_user_id = '.$idUser.' AND r.id_art_id=a.id
        ';
        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function topLike():array{
        $conn=$this->getEntityManager()->getConnection();
        $sql='SELECT id_art_id,COUNT(id) AS compteur from reagit GROUP BY(id_art_id)
        ORDER BY compteur DESC LIMIT 2';
        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function notifValidArt($idUser):array{
        $conn=$this->getEntityManager()->getConnection();
        $sql='SELECT u.nom,u.image as imageuser,u.prenom,a.*,c.categorie
        FROM article a
        INNER JOIN user u ON u.id = a.id_user
        INNER JOIN article_cat c ON a.id_cat_id = c.id
        where a.etat_ajout=0 AND  a.id_user='.$idUser.' 
        order by date_ajout desc';
        $stm=$conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function notifLike($idUser):array{
        $conn=$this->getEntityManager()->getConnection();
        $sql='SELECT DISTINCT u.id as luserlasl,u.nom,u.image as imageuser,u.prenom,a.*,r.id_user_id as idliaamljaime
        FROM user u,article a
        INNER JOIN reagit r ON r.id_art_id = a.id
        where u.id='.$idUser.' AND a.id_User='.$idUser.'
        order by date_ajout desc';
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

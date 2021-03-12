<?php

namespace App\Repository;

use App\Entity\UserWorker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserWorker|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWorker|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWorker[]    findAll()
 * @method UserWorker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWorkerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWorker::class);
    }

    // /**
    //  * @return UserWorker[] Returns an array of UserWorker objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserWorker
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

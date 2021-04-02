<?php

namespace App\Repository;

use App\Entity\RDV;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RDV|null find($id, $lockMode = null, $lockVersion = null)
 * @method RDV|null findOneBy(array $criteria, array $orderBy = null)
 * @method RDV[]    findAll()
 * @method RDV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RDVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RDV::class);
    }

    // /**
    //  * @return RDV[] Returns an array of RDV objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RDV
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByPatient($patient)
    {
        return $this->createQueryBuilder('r')
            ->join('r.patient', 'p')
            ->andWhere('p.id = :patient')
            ->setParameter('patient', $patient)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMedecin($medecin)
    {
        return $this->createQueryBuilder('r')
            ->join('r.medecin', 'm')
            ->andWhere('m.id = :medecin')
            ->setParameter('medecin', $medecin)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllEventsWithDateTomorrow()
    {
        $tomorrow = new DateTime();
        $tomorrow->modify('+1 day');

        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.etat = 1')
            ->andWhere('r.date < :tomorrow')
            ->setParameter('tomorrow', $tomorrow)
        ;

        return $qb->getQuery()->getResult();
    }

    public function findRDVByPatientMedecin($patient, $medecin) {
        return $this->createQueryBuilder('r')
            ->join('r.medecin', 'm')
            ->join('r.patient', 'p')
            ->andWhere('m.id = :medecin')
            ->andWhere('p.id = :patient')
            ->andWhere('r.etat != 3')
            ->setParameter('medecin', $medecin)
            ->setParameter('patient', $patient)
            ->getQuery()
            ->getResult()
            ;
    }
}

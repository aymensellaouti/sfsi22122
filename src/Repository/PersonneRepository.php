<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    public function getPersonneByIntervalAge($min, $max) {
        $qb = $this->createQueryBuilder('p');
        $this->addIntervalAge($qb, $min, $max);
        return $qb->getQuery()->getResult();
    }

    public function getStatsPersonneByIntervalAge($min, $max) {
        $qb = $this->createQueryBuilder('p');
        $qb->select('avg(p.age) as ageMoyen, count(p.id) as nbPersonne');
        $this->addIntervalAge($qb, $min, $max);
        return $qb->getQuery()->getScalarResult();
    }

    private function addIntervalAge(QueryBuilder $qb, $min, $max) {
            $qb->andWhere('p.age >= :minAge')
            ->andWhere('p.age <= :maxAge')
            ->setParameters([
                'minAge'=> $min,
                'maxAge'=> $max,
            ]);
    }

    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Personne
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\ProjectOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectOwner[]    findAll()
 * @method ProjectOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectOwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectOwner::class);
    }

    // /**
    //  * @return ProjectOwner[] Returns an array of ProjectOwner objects
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
    public function findOneBySomeField($value): ?ProjectOwner
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

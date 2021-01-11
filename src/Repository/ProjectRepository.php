<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Projects|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projects|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projects[]    findAll()
 * @method Projects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findNewProjects()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = true')
            ->orderBy('p.createDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findOldProjects()
    {
        $conn = $this->getEntityManager()->getConnection();

        $psql = "
                SELECT * FROM project
                WHERE create_date < CURRENT_TIMESTAMP - INTERVAL '240 hours'
                ORDER BY create_date DESC
                ";

        $stmt = $conn->prepare($psql);
        $stmt->execute();

        return $stmt->fetchAllAssociative();

        // return $this->createQueryBuilder('p')
        //     ->andWhere('p.createDate > p.createDate + INTERVAL 10 days')
        //     ->orderBy('p.createDate', 'ASC')
        //     ->setMaxResults(15)
        //     ->getQuery()
        //     ->getResult();
    }

    // /**
    //  * @return Projects[] Returns an array of Projects objects
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
    public function findOneBySomeField($value): ?Projects
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

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

    /**
     * This function generate a query and return the array of new projects
     *
     * @return void
     */
    public function findNewProjects()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = true')
            ->orderBy('p.createDate', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findOldProjects()
    {
        $conn = $this->getEntityManager()->getConnection();

        $psql = "
                SELECT p.id, p.title, p.category_id_id, p.description, p.active, p.user_id_id, u.username, up.company_name, c.label 
                FROM project AS \"p\"
                JOIN \"user\" AS \"u\" ON u.id = p.user_id_id
                JOIN user_profile AS \"up\" ON up.user_id_id = p.user_id_id
                JOIN \"category\" AS \"c\" ON c.id = p.category_id_id
                WHERE p.active = true
                AND create_date > CURRENT_TIMESTAMP - INTERVAL '21 days'
                ORDER BY create_date DESC
                LIMIT 5;
                ";

        $stmt = $conn->prepare($psql);
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }

    public function getPaginatedProjects(int $page, int $length)
    {
        $queryBuilder = $this->createQueryBuilder("p")
            ->orderBy("p.createDate", "desc")
            ->setFirstResult(($page - 1) * $length)
            ->setMaxResults($length);

        return $queryBuilder->getQuery()->getResult();
    }

    public function countActiveProjects()
    {
        return $this->createQueryBuilder("p")
            ->select("count(p.id)")
            ->getQuery()
            ->getScalarResult();
    }

    public function getOwnProjects(int $id)
    {
        return $this->createQueryBuilder("p")
            ->andWhere("p.UserId = :id")
            ->setParameter("id", $id)
            ->orderBy("p.createDate", "DESC")
            ->getQuery()
            ->getResult();
    }

    public function getProjectOwner()
    {
        return $this->createQueryBuilder("p")
            ->join("p.UserId", "u")
            ->Where("p.UserId = u.id")
            ->orderBy("p.createDate", "DESC")
            ->getQuery()
            ->getResult();
    }

    public function getCategoryLabel(int $categoryId)
    {
        return $this->createQueryBuilder("p")
            ->join("p.CategoryId", "c")
            ->where("p.CategoryId = :categoryId")
            ->setParameter("categoryId", $categoryId)
            ->orderBy("p.CategoryId")
            ->getQuery()
            ->getResult();
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

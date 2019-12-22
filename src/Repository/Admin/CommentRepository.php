<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function  getAllComments(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT com.*,usr.name,usr.surname,cr.title FROM comment com
                JOIN user usr ON usr.id = com.userid 
                JOIN car cr ON cr.id = com.carid 
                ORDER BY cr.id DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function  getUserComments($userId): array
    {
        $qb= $this->createQueryBuilder('com')
            ->select('com.id,com.subject,com.rate,com.comment,com.created_at,com.carid,com.status,cr.title')
            ->leftJoin('App\Entity\Car','cr','WITH','cr.id = com.carid')
            ->where('com.userid = :userid')
            ->setParameter('userid',$userId)
            ->orderBy('com.id','DESC');
        $query =$qb->getQuery();
        return $query->execute();
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

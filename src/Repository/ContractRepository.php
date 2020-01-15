<?php

namespace App\Repository;

use App\Entity\Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[]    findAll()
 * @method Contract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    public function getContracts($status): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT usr.name,usr.surname,cr.title,cnt.* 
                FROM contract cnt 
                JOIN user usr ON usr.id = cnt.customer_id 
                JOIN car cr ON cr.id = cnt.car_id
                WHERE cnt.status = :status 
                ORDER BY cnt.id DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll();
    }
    public function getContractsForUser($id): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT cr.id AS carid,cr.title,cr.image,cnt.*
                FROM contract cnt
                JOIN car cr ON cr.id = cnt.car_id
                WHERE cnt.customer_id = :userid
                ORDER BY cnt.id DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['userid' => $id]);
        return $stmt->fetchAll();
    }
    public function getContractToShow($id): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT cr.id AS carid,cr.title,cr.image,cnt.*
                FROM contract cnt
                JOIN car cr ON cr.id = cnt.car_id
                WHERE cnt.id = :id
                ORDER BY cnt.id DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }
    // /**
    //  * @return Contract[] Returns an array of Contract objects
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
    public function findOneBySomeField($value): ?Contract
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

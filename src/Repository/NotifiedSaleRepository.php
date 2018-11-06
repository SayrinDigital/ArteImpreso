<?php

namespace App\Repository;

use App\Entity\NotifiedSale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NotifiedSale|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotifiedSale|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotifiedSale[]    findAll()
 * @method NotifiedSale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotifiedSaleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NotifiedSale::class);
    }

//    /**
//     * @return NotifiedSale[] Returns an array of NotifiedSale objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NotifiedSale
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\HelpTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HelpTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method HelpTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method HelpTicket[]    findAll()
 * @method HelpTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HelpTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HelpTicket::class);
    }

    // /**
    //  * @return HelpTicket[] Returns an array of HelpTicket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HelpTicket
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Parameter;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function findvrijekamers($value)
    {

        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.check_in_date <= :checkout')
            ->andWhere('r.check_out_date >= :checkin')
            ->setParameter('checkin', $value['checkin'])
            ->setParameter('checkout', $value['checkout'])
            ->orderBy('r.room_id', 'ASC')
        ;

        dump ($qb->getQuery()->getResult()) ;

        return $qb->getQuery()->getResult();
    }
    
    // /**
    //  * @param $startDate
    //  * @param $endDate
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    // public function findReservationsBetween($startDate, $endDate)
    // {
    //     return $this->createQueryBuilder('b')
    //         ->select("IDENTITY(b.room)")
    //         ->orWhere('(b.start_date BETWEEN :start AND :end)')
    //         ->orWhere('(b.end_date BETWEEN :start AND :end)')
    //         ->orWhere('(b.start_date <= :start and b.end_date >= :end)')
    //         ->setParameters(new ArrayCollection([
    //             new Parameter("start", $startDate),
    //             new Parameter("end", $endDate)
    //         ]))
    //         ->getQuery()
    //         ->getResult();
    // }


    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

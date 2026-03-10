<?php

namespace App\Repository;

use App\Entity\Adherent;
use App\Entity\Livre;
use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function countReservationsActivesByAdherent(Adherent $adherent): int
    {
        $dateExpiration = new \DateTime('-7 days');

        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.adherent = :adherent')
            ->andWhere('r.dateReservation >= :dateExpiration')
            ->setParameter('adherent', $adherent)
            ->setParameter('dateExpiration', $dateExpiration)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findActiveReservationForLivre(Livre $livre): ?Reservation
    {
        $dateExpiration = new \DateTime('-7 days');

        return $this->createQueryBuilder('r')
            ->andWhere('r.livre = :livre')
            ->andWhere('r.dateReservation >= :dateExpiration')
            ->setParameter('livre', $livre)
            ->setParameter('dateExpiration', $dateExpiration)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findActiveReservationByAdherentAndLivre(Adherent $adherent, Livre $livre): ?Reservation
    {
        $dateExpiration = new \DateTime('-7 days');

        return $this->createQueryBuilder('r')
            ->andWhere('r.adherent = :adherent')
            ->andWhere('r.livre = :livre')
            ->andWhere('r.dateReservation >= :dateExpiration')
            ->setParameter('adherent', $adherent)
            ->setParameter('livre', $livre)
            ->setParameter('dateExpiration', $dateExpiration)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

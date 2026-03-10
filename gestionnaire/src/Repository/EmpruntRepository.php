<?php

namespace App\Repository;

use App\Entity\Adherent;
use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    public function findEmpruntsEnCours(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.dateRetourEffective IS NULL')
            ->orderBy('e.dateEmprunt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findEmpruntsEnRetard(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.dateRetourEffective IS NULL')
            ->andWhere('e.dateRetourPrevue < :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('e.dateRetourPrevue', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countEmpruntsEnCoursByAdherent(Adherent $adherent): int
    {
        return (int) $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->andWhere('e.adherent = :adherent')
            ->andWhere('e.dateRetourEffective IS NULL')
            ->setParameter('adherent', $adherent)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

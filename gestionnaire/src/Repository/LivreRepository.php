<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function findBySearchCriteria(?string $titre, ?int $auteurId, ?int $categorieId, ?string $langue, ?string $dateDebut, ?string $dateFin, int $page = 1, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('l')
            ->addSelect('a', 'c')
            ->leftJoin('l.auteurs', 'a')
            ->leftJoin('l.categorie', 'c');

        if ($titre) {
            $qb->andWhere('l.titre LIKE :titre')
               ->setParameter('titre', '%' . $titre . '%');
        }
        if ($auteurId) {
            $qb->andWhere('a.id = :auteurId')
               ->setParameter('auteurId', $auteurId);
        }
        if ($categorieId) {
            $qb->andWhere('c.id = :categorieId')
               ->setParameter('categorieId', $categorieId);
        }
        if ($langue) {
            $qb->andWhere('l.langue = :langue')
               ->setParameter('langue', $langue);
        }
        if ($dateDebut) {
            $qb->andWhere('l.dateSortie >= :dateDebut')
               ->setParameter('dateDebut', $dateDebut);
        }
        if ($dateFin) {
            $qb->andWhere('l.dateSortie <= :dateFin')
               ->setParameter('dateFin', $dateFin);
        }

        $qb->orderBy('l.titre', 'ASC')
           ->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        $paginator = new Paginator($qb, true);
        $total = count($paginator);

        return ['livres' => iterator_to_array($paginator), 'total' => $total];
    }
}

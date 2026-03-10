<?php

namespace App\Controller\Api;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class LivreController extends AbstractController
{
    #[Route('/livres', name: 'api_livres', methods: ['GET'])]
    public function index(Request $request, LivreRepository $livreRepository): JsonResponse
    {
        $titre = $request->query->get('titre');
        $auteurId = $request->query->get('auteurId') ? (int)$request->query->get('auteurId') : null;
        $categorieId = $request->query->get('categorieId') ? (int)$request->query->get('categorieId') : null;
        $langue = $request->query->get('langue');
        $dateDebut = $request->query->get('dateDebut');
        $dateFin = $request->query->get('dateFin');
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = max(1, min(50, (int)$request->query->get('limit', 10)));

        $result = $livreRepository->findBySearchCriteria(
            $titre, $auteurId, $categorieId, $langue, $dateDebut, $dateFin, $page, $limit
        );

        return $this->json([
            'livres' => $result['livres'],
            'total' => $result['total'],
            'page' => $page,
            'limit' => $limit,
            'totalPages' => ceil($result['total'] / $limit),
        ], 200, [], ['groups' => 'livre:list']);
    }

    #[Route('/livres/{id}', name: 'api_livres_show', methods: ['GET'])]
    public function show(Livre $livre): JsonResponse
    {
        return $this->json($livre, 200, [], ['groups' => 'livre:read']);
    }

    #[Route('/livres/langues', name: 'api_livres_langues', methods: ['GET'], priority: 10)]
    public function langues(LivreRepository $livreRepository): JsonResponse
    {
        $qb = $livreRepository->createQueryBuilder('l')
            ->select('DISTINCT l.langue')
            ->orderBy('l.langue', 'ASC');

        $results = $qb->getQuery()->getScalarResult();
        $langues = array_column($results, 'langue');

        return $this->json($langues);
    }
}

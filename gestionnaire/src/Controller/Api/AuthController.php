<?php

namespace App\Controller\Api;

use App\Entity\Adherent;
use App\Entity\Reservation;
use App\Repository\EmpruntRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class AuthController extends AbstractController
{
    #[Route('/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login(): JsonResponse
    {
        // Route gérée par le firewall json_login
        return $this->json(['message' => 'Missing credentials'], 401);
    }

    #[Route('/user/me', name: 'api_user_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var Adherent|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Non authentifié'], 401);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'telephone' => $user->getTelephone(),
            'adresse' => $user->getAdresse(),
            'dateInscription' => $user->getDateInscription()?->format('Y-m-d'),
            'actif' => $user->isActif(),
        ]);
    }

    #[IsGranted('ROLE_ADHERENT')]
    #[Route('/user/profil', name: 'api_user_profil_update', methods: ['PUT'])]
    public function updateProfil(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var Adherent $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['telephone'])) {
            $user->setTelephone($data['telephone']);
        }
        if (isset($data['adresse'])) {
            $user->setAdresse($data['adresse']);
        }

        $em->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'telephone' => $user->getTelephone(),
            'adresse' => $user->getAdresse(),
            'dateInscription' => $user->getDateInscription()?->format('Y-m-d'),
            'actif' => $user->isActif(),
        ]);
    }

    #[IsGranted('ROLE_ADHERENT')]
    #[Route('/user/emprunts', name: 'api_user_emprunts', methods: ['GET'])]
    public function mesEmprunts(EmpruntRepository $empruntRepository): JsonResponse
    {
        /** @var Adherent $user */
        $user = $this->getUser();

        $emprunts = $empruntRepository->findBy(
            ['adherent' => $user],
            ['dateEmprunt' => 'DESC']
        );

        return $this->json($emprunts, 200, [], ['groups' => 'emprunt:read']);
    }

    #[IsGranted('ROLE_ADHERENT')]
    #[Route('/user/reservations', name: 'api_user_reservations', methods: ['GET'])]
    public function mesReservations(ReservationRepository $reservationRepository): JsonResponse
    {
        /** @var Adherent $user */
        $user = $this->getUser();

        $reservations = $reservationRepository->findBy(
            ['adherent' => $user],
            ['dateReservation' => 'DESC']
        );

        return $this->json($reservations, 200, [], ['groups' => 'reservation:read']);
    }

    #[IsGranted('ROLE_ADHERENT')]
    #[Route('/reservations', name: 'api_reservations_create', methods: ['POST'])]
    public function creerReservation(
        Request $request,
        EntityManagerInterface $em,
        ReservationRepository $reservationRepository
    ): JsonResponse {
        /** @var Adherent $user */
        $user = $this->getUser();

        if (!$user->isActif()) {
            return $this->json(['message' => 'Votre compte est suspendu'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $livreId = $data['livreId'] ?? null;

        if (!$livreId) {
            return $this->json(['message' => 'ID du livre requis'], 400);
        }

        $livre = $em->getRepository(\App\Entity\Livre::class)->find($livreId);
        if (!$livre) {
            return $this->json(['message' => 'Livre introuvable'], 404);
        }

        // Vérifier que le livre est disponible (pas emprunté)
        if (!$livre->isDisponible()) {
            return $this->json(['message' => 'Ce livre est actuellement emprunté'], 409);
        }

        // Vérifier que l'adhérent n'a pas déjà réservé ce livre
        $dejaReserve = $reservationRepository->findActiveReservationByAdherentAndLivre($user, $livre);
        if ($dejaReserve) {
            return $this->json(['message' => 'Vous avez déjà réservé ce livre'], 409);
        }

        // Vérifier qu'il n'y a pas déjà une réservation active sur ce livre par un autre adhérent
        $reservationExistante = $reservationRepository->findActiveReservationForLivre($livre);
        if ($reservationExistante) {
            return $this->json(['message' => 'Ce livre est déjà réservé par un autre adhérent'], 409);
        }

        // Vérifier la limite de 3 réservations simultanées
        $nbReservations = $reservationRepository->countReservationsActivesByAdherent($user);
        if ($nbReservations >= 3) {
            return $this->json(['message' => 'Vous avez déjà 3 réservations en cours (maximum atteint)'], 409);
        }

        $reservation = new Reservation();
        $reservation->setAdherent($user);
        $reservation->setLivre($livre);
        $reservation->setDateReservation(new \DateTime());

        $em->persist($reservation);
        $em->flush();

        return $this->json([
            'message' => 'Réservation créée avec succès',
            'id' => $reservation->getId(),
        ], 201);
    }

    #[IsGranted('ROLE_ADHERENT')]
    #[Route('/reservations/{id}', name: 'api_reservations_delete', methods: ['DELETE'])]
    public function annulerReservation(Reservation $reservation, EntityManagerInterface $em): JsonResponse
    {
        /** @var Adherent $user */
        $user = $this->getUser();

        // Vérifier que la réservation appartient bien à l'adhérent connecté
        if ($reservation->getAdherent()->getId() !== $user->getId()) {
            return $this->json(['message' => 'Vous ne pouvez pas annuler cette réservation'], 403);
        }

        $em->remove($reservation);
        $em->flush();

        return $this->json(['message' => 'Réservation annulée']);
    }
}

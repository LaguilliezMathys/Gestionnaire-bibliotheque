<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private EntityManagerInterface $em,
        private EmpruntRepository $empruntRepository
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $nbLivres = $this->em->getRepository(Livre::class)->count([]);
        $nbAuteurs = $this->em->getRepository(Auteur::class)->count([]);
        $nbCategories = $this->em->getRepository(Categorie::class)->count([]);
        $nbAdherents = $this->em->getRepository(Adherent::class)->count(['actif' => true]);
        $nbEmpruntsEnCours = count($this->empruntRepository->findEmpruntsEnCours());
        $nbEmpruntsEnRetard = count($this->empruntRepository->findEmpruntsEnRetard());
        $nbReservations = $this->em->getRepository(Reservation::class)->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'nbLivres' => $nbLivres,
            'nbAuteurs' => $nbAuteurs,
            'nbCategories' => $nbCategories,
            'nbAdherents' => $nbAdherents,
            'nbEmpruntsEnCours' => $nbEmpruntsEnCours,
            'nbEmpruntsEnRetard' => $nbEmpruntsEnRetard,
            'nbReservations' => $nbReservations,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bibliothèque - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');

        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Livres', 'fas fa-book', Livre::class);
        yield MenuItem::linkToCrud('Auteurs', 'fas fa-pen-fancy', Auteur::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Categorie::class);

        yield MenuItem::section('Gestion');
        yield MenuItem::linkToCrud('Emprunts', 'fas fa-exchange-alt', Emprunt::class);
        yield MenuItem::linkToCrud('Réservations', 'fas fa-clock', Reservation::class);
        yield MenuItem::linkToCrud('Adhérents', 'fas fa-users', Adherent::class);

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Administration');
            yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user-shield', Utilisateur::class);
        }
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->displayUserName()
            ->displayUserAvatar()
            ->setMenuItems([]);
    }
}

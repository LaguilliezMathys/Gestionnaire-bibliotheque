<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\RequestStack;

class EmpruntCrudController extends AbstractCrudController
{
    public function __construct(
        private EmpruntRepository $empruntRepository,
        private RequestStack $requestStack
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Emprunt::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('adherent', 'Adhérent'),
            AssociationField::new('livre'),
            DateTimeField::new('dateEmprunt', 'Date d\'emprunt')
                ->hideOnForm(),
            DateField::new('dateRetourPrevue', 'Retour prévu')
                ->hideOnForm(),
            DateTimeField::new('dateRetourEffective', 'Retour effectif')
                ->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $retourAction = Action::new('retour', 'Enregistrer retour', 'fas fa-undo')
            ->linkToCrudAction('enregistrerRetour')
            ->displayIf(static function (Emprunt $entity) {
                return $entity->isEnCours();
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $retourAction)
            ->add(Crud::PAGE_DETAIL, $retourAction);
    }

    public function enregistrerRetour(): \Symfony\Component\HttpFoundation\Response
    {
        $context = $this->getContext();
        $empruntId = $this->requestStack->getCurrentRequest()->query->get('entityId');

        $em = $this->container->get('doctrine')->getManager();
        $emprunt = $em->getRepository(Emprunt::class)->find($empruntId);

        if ($emprunt && $emprunt->isEnCours()) {
            $emprunt->setDateRetourEffective(new \DateTime());
            // Remettre le livre disponible
            $emprunt->getLivre()->setDisponible(true);
            $em->flush();
            $this->addFlash('success', 'Retour enregistré pour : ' . $emprunt->getLivre()->getTitre());
        }

        return $this->redirect($this->container->get('router')->generate('admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => self::class,
        ]));
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Emprunt) {
            // Vérifier la limite de 5 emprunts en cours
            $nbEmpruntsEnCours = $this->empruntRepository->countEmpruntsEnCoursByAdherent($entityInstance->getAdherent());
            if ($nbEmpruntsEnCours >= 5) {
                $this->addFlash('danger', 'Cet adhérent a déjà 5 emprunts en cours. Impossible d\'en ajouter un nouveau.');
                return;
            }

            // Vérifier la disponibilité du livre
            if (!$entityInstance->getLivre()->isDisponible()) {
                $this->addFlash('danger', 'Ce livre n\'est pas disponible actuellement.');
                return;
            }

            // Remplir automatiquement les dates
            $entityInstance->setDateEmprunt(new \DateTime());
            $entityInstance->setDateRetourPrevue(new \DateTime('+15 days'));

            // Rendre le livre indisponible
            $entityInstance->getLivre()->setDisponible(false);

            // Supprimer une éventuelle réservation de cet adhérent pour ce livre
            $reservations = $entityInstance->getLivre()->getReservations();
            foreach ($reservations as $reservation) {
                if ($reservation->getAdherent() === $entityInstance->getAdherent()) {
                    $entityManager->remove($reservation);
                }
            }
        }
        parent::persistEntity($entityManager, $entityInstance);
    }
}

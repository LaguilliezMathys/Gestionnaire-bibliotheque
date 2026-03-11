<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityManagerInterface $em,
        private AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('adherent', 'Adhérent'),
            AssociationField::new('livre'),
            DateTimeField::new('dateReservation', 'Date de réservation')
                ->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $convertirEnEmprunt = Action::new('convertirEnEmprunt', 'Convertir en emprunt', 'fas fa-exchange-alt')
            ->linkToCrudAction('convertirEnEmprunt')
            ->setCssClass('btn btn-success');

        return $actions
            ->add(Crud::PAGE_INDEX, $convertirEnEmprunt)
            ->add(Crud::PAGE_DETAIL, $convertirEnEmprunt)
            ->addBatchAction(Action::new('convertirEnEmpruntBatch', 'Convertir en emprunts', 'fas fa-exchange-alt')
                ->linkToCrudAction('convertirEnEmpruntBatch')
                ->setCssClass('btn btn-success'));
    }

    public function convertirEnEmprunt(): Response
    {
        /** @var Reservation $reservation */
        $reservation = $this->getContext()->getEntity()->getInstance();

        $livre = $reservation->getLivre();
        $adherent = $reservation->getAdherent();

        // Créer l'emprunt
        $emprunt = new Emprunt();
        $emprunt->setLivre($livre);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourPrevue(new \DateTime('+21 days'));

        // Marquer le livre comme indisponible
        $livre->setDisponible(false);

        $this->em->persist($emprunt);
        $this->em->remove($reservation);
        $this->em->flush();

        $this->addFlash('success', sprintf(
            'La réservation a été convertie en emprunt. "%s" est emprunté par %s jusqu\'au %s.',
            $livre->getTitre(),
            $adherent,
            $emprunt->getDateRetourPrevue()->format('d/m/Y')
        ));

        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function convertirEnEmpruntBatch(Request $request): Response
    {
        $ids = $request->request->all('batchActionEntityIds');
        $reservations = $this->em->getRepository(Reservation::class)->findBy(['id' => $ids]);
        $count = 0;

        foreach ($reservations as $reservation) {
            $livre = $reservation->getLivre();
            $adherent = $reservation->getAdherent();

            $emprunt = new Emprunt();
            $emprunt->setLivre($livre);
            $emprunt->setAdherent($adherent);
            $emprunt->setDateEmprunt(new \DateTime());
            $emprunt->setDateRetourPrevue(new \DateTime('+21 days'));

            $livre->setDisponible(false);

            $this->em->persist($emprunt);
            $this->em->remove($reservation);
            $count++;
        }

        $this->em->flush();

        $this->addFlash('success', sprintf(
            '%d réservation(s) convertie(s) en emprunt(s).',
            $count
        ));

        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}

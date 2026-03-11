<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdherentCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Adherent::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom', 'Prénom'),
            TextField::new('email'),
            TextField::new('password', 'Mot de passe')
                ->onlyOnForms()
                ->setFormTypeOption('mapped', false)
                ->setRequired($pageName === 'new')
                ->setHelp('Laissez vide pour ne pas modifier le mot de passe'),
            DateField::new('dateNaissance', 'Date de naissance'),
            TextField::new('numTel', 'Téléphone'),
            TextField::new('adressePostale', 'Adresse postale'),
            TextField::new('photo', 'URL photo')->hideOnIndex(),
            DateTimeField::new('dateAdhesion', 'Date d\'adhésion')
                ->hideOnForm(),
            BooleanField::new('actif'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Adherent) {
            $plainPassword = $this->getContext()->getRequest()->request->all('Adherent')['password'] ?? '';
            if (!empty($plainPassword)) {
                $entityInstance->setPassword(
                    $this->passwordHasher->hashPassword($entityInstance, $plainPassword)
                );
            }
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Adherent) {
            $plainPassword = $this->getContext()->getRequest()->request->all('Adherent')['password'] ?? '';
            if (!empty($plainPassword)) {
                $entityInstance->setPassword(
                    $this->passwordHasher->hashPassword($entityInstance, $plainPassword)
                );
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }
}

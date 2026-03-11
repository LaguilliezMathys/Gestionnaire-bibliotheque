<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom', 'Prénom'),
            DateField::new('dateNaissance', 'Date de naissance'),
            DateField::new('dateDeces', 'Date de décès')->hideOnIndex(),
            TextField::new('nationalite', 'Nationalité'),
            TextareaField::new('description')->hideOnIndex(),
            TextField::new('photo', 'URL photo')->hideOnIndex(),
        ];
    }
}

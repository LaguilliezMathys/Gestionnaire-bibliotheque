<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LivreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('isbn', 'ISBN'),
            TextareaField::new('resume', 'Résumé')->hideOnIndex(),
            TextField::new('langue'),
            DateField::new('dateSortie', 'Date de sortie'),
            TextField::new('couverture', 'URL couverture')->hideOnIndex(),
            BooleanField::new('disponible'),
            AssociationField::new('categorie'),
            AssociationField::new('auteurs'),
        ];
    }
}

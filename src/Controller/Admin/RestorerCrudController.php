<?php

namespace App\Controller\Admin;

use App\Entity\Restorer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class RestorerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Restorer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('is_verified', 'Activer le restaurateur')->renderAsSwitch(false),
            IdField::new('id')->setDisabled(),
            Field::new('firstname', 'Prénom'),
            Field::new('lastname', 'Nom'),
            EmailField::new('email', 'Email'),
            Field::new('phone_number', 'Téléphone'),
        ];
    }
}


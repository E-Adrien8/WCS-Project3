<?php

namespace App\Controller\Admin;

use App\DBAL\Types\SexType;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('is_verified', 'Activer l\'utilisateur')->renderAsSwitch(false),
            BooleanField::new('is_admin', 'Rôle Admin à l\'utilisateur')->renderAsSwitch(false),
            IdField::new('id')->setDisabled(),
            Field::new('firstname', 'Prénom'),
            Field::new('lastname', 'Nom'),
            Field::new('user_name', 'Pseudo'),
            Field::new('email', 'Email'),
            DateField::new('birthdate', 'Date d\'anniversaire')->renderAsChoice(),
            Field::new('city', 'Ville'),
            ChoiceField::new('sex', 'Sexe')->setChoices(SexType::getChoices()),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\FoodType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FoodTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FoodType::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

<?php

namespace App\Controller\Admin;

use App\DBAL\Types\MealTimeType;
use App\Entity\Event;
use App\Entity\Restaurant;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class EventCrudController extends AbstractCrudController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $someRepository = $this->entityManager->getRepository(Restaurant::class);
        return [
            IdField::new('id')->setDisabled(),
            AssociationField::new('restaurant', 'Restaurant Id')->onlyOnIndex(),
            Field::new('date', 'Date de publication'),
            Field::new('places', 'Nombre de place'),
            Field::new('theme', 'Thème de l\'évènement'),
            ChoiceField::new('meal', 'Déjeuner / Dîner')->setChoices(MealTimeType::getChoices())
        ];
    }
}

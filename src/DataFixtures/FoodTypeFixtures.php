<?php

namespace App\DataFixtures;

use App\Entity\FoodType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FoodTypeFixtures extends Fixture
{
    public const CATEGORIES = [
        'Grec',
        'Bistronomique',
        'Gastonomique',
        'Vietnamien',
        'Anglais',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $foodName) {
            $foodType = new FoodType();
            $foodType->setName($foodName);

            $this->addReference('foodtype-' . $foodName, $foodType);

            $manager->persist($foodType);
        }

        $manager->flush();
    }
}

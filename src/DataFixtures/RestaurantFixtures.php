<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RestaurantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $restaurant = new Restaurant();
            $restaurant->setRestorer($this->getReference('restorer-' . $i));
            $restaurant->setName($faker->name());
            $restaurant->setAddress($faker->address());
            $restaurant->setZipCode($faker->postcode());
            $restaurant->setCity($faker->city());
            $restaurant->setMenuText($faker->paragraphs(2, true));
            $restaurant->setSiret((string) $faker->randomNumber(5));
            $restaurant->setPhoneNumber($faker->phoneNumber());
            $restaurant->setEmail($faker->email());
            $restaurant->setFoodType($this->getReference('foodtype-' . $faker->randomElement(FoodTypeFixtures::CATEGORIES)));
            $restaurant->setZone($this->getReference('zone-' . rand(0, 9)));
            $restaurant->setDescription($faker->paragraph());
            $manager->persist($restaurant);

            $this->addReference('restaurant-' . $i, $restaurant);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestorerFixtures::class,
            FoodTypeFixtures::class,
            ZoneFixtures::class,
        ];
    }
}

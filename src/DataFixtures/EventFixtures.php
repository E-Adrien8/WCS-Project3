<?php

namespace App\DataFixtures;

use App\DBAL\Types\MealTimeType;
use App\Entity\Event;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event->setDate($faker->dateTimeBetween('yesterday', '+7 days'));
            $event->setPlaces($faker->numberBetween(2, 8));
            $event->setMeal($faker->randomElement(MealTimeType::getChoices()));
            $event->setRestaurant($this->getReference('restaurant-' . $faker->numberBetween(0, 9)));
            $event->setTheme('theme');
            $event->addAttendee($this->getReference('user-' . $faker->numberBetween(0, 4)));
            $event->addAttendee($this->getReference('user-' . $faker->numberBetween(5, 9)));
            $event->setTime($faker->numberBetween(0, 23) . ':' .
                $faker->randomElement(range(0, 55, 5)));

            $manager->persist($event);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
            UserFixtures::class,
        ];
    }
}

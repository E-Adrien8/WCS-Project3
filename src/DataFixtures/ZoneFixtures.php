<?php

namespace App\DataFixtures;

use App\Entity\Zone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ZoneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $zone = new Zone();
            $zone->setCity($faker->city());
            $manager->persist($zone);

            $this->addReference('zone-' . $i, $zone);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Restorer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RestorerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $restorer = new Restorer();
            $restorer->setFirstname($faker->firstName());
            $restorer->setLastname($faker->lastName());
            $restorer->setEmail($faker->email());
            $restorer->setPhoneNumber($faker->phoneNumber());
            $restorer->setPassword($faker->password());

            $this->addReference('restorer-' . $i, $restorer);

            $manager->persist($restorer);
        }
        $manager->flush();
    }
}

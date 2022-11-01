<?php

namespace App\DataFixtures;

use App\DBAL\Types\SexType;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setUserName($faker->userName());
            $user->setBirthdate(new DateTime($faker->date()));
            $user->setEmail($faker->email());
            $user->setPassword(password_hash('test', PASSWORD_DEFAULT));
            $user->setSex($faker->randomElement(SexType::getChoices()));

            $manager->persist($user);

            $this->addReference('user-' . $i, $user);
        }

        $manager->flush();
    }
}

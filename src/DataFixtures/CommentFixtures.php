<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $comment->setUser($this->getReference('user-' . $faker->numberBetween(0, 9)));
            $comment->setRestaurant($this->getReference('restaurant-' . $faker->numberBetween(0, 9)));
            $comment->setComment($faker->text());
            $comment->setDate($faker->dateTime());

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            RestaurantFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PersonneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i=0 ; $i<30 ; $i++) {
            $personne = new Personne();
            $personne->setName($faker->name);
            $personne->setFirstname($faker->firstName);
            $personne->setAge(mt_rand(18, 70));
            $manager->persist($personne);
        }
        $manager->flush();
    }
}

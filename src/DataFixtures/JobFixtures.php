<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class JobFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $jobs = [
            "Data scientist",
            "Statisticien",
            "Analyste cyber-sécurité",
            "Médecin ORL",
            "Échographiste",
            "Mathématicien",
            "Ingénieur logiciel",
            "Analyste informatique",
            "Pathologiste du discours / langage",
            "Actuaire",
            "Ergothérapeute",
            "Directeur des Ressources Humaines",
            "Hygiéniste dentaire "
        ];
        for ($i = 0; $i < count($jobs); $i++) {
            $job = new Job();
            $job->setName($jobs[$i]);
            $manager->persist($job);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['job'];
    }
}

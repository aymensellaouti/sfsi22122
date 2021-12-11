<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $admin1 = new User();
        $admin1->setEmail('admin@gmail.com')
               ->setRoles(['ROLE_ADMIN'])
               ->setPassword(
                   $this->hasher->hashPassword(
                       $admin1,
                       'admin'
                   )
               );
        $admin2 = new User();
        $admin2->setEmail('admin2@gmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->hasher->hashPassword(
                    $admin1,
                    'admin'
                )
            );
        $manager->persist($admin1);
        $manager->persist($admin2);

        for($i=1; $i <= 10 ; $i++) {
            $user = new User();
            $user->setEmail("user$i@gmail.com")
                ->setPassword(
                    $this->hasher->hashPassword(
                        $admin1,
                        'user'
                    )
                );
            $manager->persist($user);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}

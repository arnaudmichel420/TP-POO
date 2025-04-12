<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 11; $i++) {

            $user = new User();
            $user->setEmail("user" . $i . "@test.com");
            $user->setPassword($this->passwordHasher->hashPassword($user, "user" . $i));

            $manager->persist($user);
        }

        $manager->flush();
    }
}

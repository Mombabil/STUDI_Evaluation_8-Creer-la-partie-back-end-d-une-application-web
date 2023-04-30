<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // on crée un faux admin via une fixture
        $admin = new User($this->passwordHasher);
        $admin->setName('perret')
            ->setFirstname('morgan')
            ->setEmail("morgan.perret.7@gmail.com")
            ->setPassword("123")
            ->setRole(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}

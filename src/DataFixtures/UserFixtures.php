<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("Mary K.");
        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername("Allison E.");
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername("Morgan R.");
        $manager->persist($user3);

        $manager->flush();

        $this->addReference("user", $user);
        $this->addReference("user2", $user2);
        $this->addReference("user3", $user3);
    }
}

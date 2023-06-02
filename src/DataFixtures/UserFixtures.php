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
        $user->setUserImage("build/images/avatar_1.fe6f03b7.jpg");
        $user->setAddress("Tallahassee, United States");
        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername("Allison E.");
        $user2->setUserImage("build/images/avatar_2.0a664683.jpg");
        $user2->setAddress("Santa Barbara, United States");
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername("Morgan R.");
        $user3->setUserImage("build/images/avatar_3.662404f0.jpg");
        $user3->setAddress("Lincoln, United States");
        $manager->persist($user3);

        $user4 = new User();
        $user4->setUsername("Pedro B.");
        $user4->setUserImage("build/images/avatar_4.553187e6.jpg");
        $user4->setAddress("Chicago, United States");
        $manager->persist($user4);

        $user5 = new User();
        $user5->setUsername("Ti V.");
        $user5->setUserImage("build/images/avatar_5.db9d8650.jpg");
        $user5->setAddress("Baltimore, United States");
        $manager->persist($user5);

        $user6 = new User();
        $user6->setUsername("Pete B.");
        $user6->setUserImage("build/images/avatar_6.134f3f2f.jpg");
        $user6->setAddress("San Francisco, United States");
        $manager->persist($user6);

        $user7 = new User();
        $user7->setUsername("Thomas M.");
        $user7->setUserImage("build/images/avatar_7.3e9e5bae.jpg");
        $user7->setAddress("MD, United States");
        $manager->persist($user7);

        $user8 = new User();
        $user8->setUsername("Motty S.");
        $user8->setUserImage("build/images/avatar_8.55b0d884.jpg");
        $user8->setAddress("San Francisco Bay Area, United States");
        $manager->persist($user8);

        $user9 = new User();
        $user9->setUsername("Faisal S.");
        $user9->setUserImage("build/images/avatar_9.2222d5b9.jpg");
        $user9->setAddress("Scotts Valley, United States");
        $manager->persist($user9);

        $user10 = new User();
        $user10->setUsername("Lauren V.");
        $user10->setUserImage("build/images/avatar_10.257cf234.jpg");
        $user10->setAddress("Colorado Springs, United States");
        $manager->persist($user10);

        $manager->flush();

        $this->addReference("user", $user);
        $this->addReference("user2", $user2);
        $this->addReference("user3", $user3);
        $this->addReference("user4", $user4);
        $this->addReference("user5", $user5);
        $this->addReference("user6", $user6);
        $this->addReference("user7", $user7);
        $this->addReference("user8", $user8);
        $this->addReference("user9", $user9);
        $this->addReference("user10", $user10);
    }
}

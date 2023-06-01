<?php

namespace App\DataFixtures;

use App\Entity\Business;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BusinessFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $business = new Business();
        $business->setName("Bleecker");
        $business->setLocation("London");
        $business->setAddress("Unit B SP 4");
        $business->setWebsite("http://www.bleeckerburger.co.uk");
        $business->setPhoneNumber("020 7998 8650");
        $business->setHours(
            [
                "Mon" => "11:30 AM - 9:00 PM",
                "Tue" => "11:30 AM - 9:00 PM",
                "Wed" => "11:30 AM - 9:00 PM",
                "Thu" => "11:30 AM - 9:00 PM",
                "Fri" => "11:30 AM - 9:00 PM",
                "Sat" => "11:30 AM - 9:00 PM",
                "Sun" => "11:30 AM - 9:00 PM",
            ]
        );
        $business->addCategory($this->getReference("category"));
        $business->addCategory($this->getReference("category2"));
        $manager->persist($business);

        $business2 = new Business();
        $business2->setName("Dirty Bones");
        $business2->setLocation("London");
        $business2->setAddress("20 Kensington Church St");
        $business2->setWebsite("http://www.dirty-bones.com");
        $business2->setPhoneNumber("020 3019 9061");
        $business2->setHours(
            [
                "Mon" => "5:00 PM - 12:00 AM",
                "Tue" => "5:00 PM - 12:00 AM",
                "Wed" => "5:00 PM - 12:00 AM",
                "Thu" => "5:00 PM - 12:00 AM",
                "Fri" => "5:00 PM - 1:00 AM",
                "Sat" => "11:00 AM - 11:00 PM",
                "Sun" => "11:00 AM - 11:00 PM",
            ]
        );
        $business2->addCategory($this->getReference("category"));
        $business2->addCategory($this->getReference("category2"));
        $manager->persist($business2);

        $business3 = new Business();
        $business3->setName("Patty & Bun");
        $business3->setLocation("London");
        $business3->setAddress("54 James Street");
        $business3->setWebsite("http://www.pattyandbun.co.uk");
        $business3->setPhoneNumber("020 7487 3188");
        $business3->setHours(
            [
                "Mon" => "12:00 AM - 11:00 PM",
                "Tue" => "12:00 AM - 11:00 PM",
                "Wed" => "12:00 AM - 11:00 PM",
                "Thu" => "12:00 AM - 11:00 PM",
                "Fri" => "12:00 AM - 11:00 PM",
                "Sat" => "12:00 AM - 11:00 PM",
                "Sun" => "12:00 AM - 10:00 PM",
            ]
        );
        $business3->addCategory($this->getReference("category"));
        $business3->addCategory($this->getReference("category2"));
        $manager->persist($business3);

        $manager->flush();

        $this->addReference("business", $business);
        $this->addReference("business2", $business2);
        $this->addReference("business3", $business3);
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}

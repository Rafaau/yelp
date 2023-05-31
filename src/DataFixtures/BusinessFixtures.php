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
        $business->addCategory($this->getReference("category"));
        $manager->persist($business);

        $business2 = new Business();
        $business2->setName("Dirty Bones Soho");
        $business2->setLocation("London");
        $business2->addCategory($this->getReference("category"));
        $manager->persist($business2);

        $business3 = new Business();
        $business3->setName("Patty & Bun");
        $business3->setLocation("London");
        $business3->addCategory($this->getReference("category"));
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

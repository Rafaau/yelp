<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName("Restaurants");
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName("Burgers");
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName("Delivery");
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setName("Reservations");
        $manager->persist($category4);

        $category5 = new Category();
        $category5->setName("Japanese");
        $manager->persist($category5);

        $category6 = new Category();
        $category6->setName("Chinese");
        $manager->persist($category6);

        $category7 = new Category();
        $category7->setName("Mexican");
        $manager->persist($category7);

        $category8 = new Category();
        $category8->setName("Pizza");
        $manager->persist($category8);

        $category9 = new Category();
        $category9->setName("Chinese");
        $manager->persist($category9);

        $category10 = new Category();
        $category10->setName("Italian");
        $manager->persist($category10);

        $category11 = new Category();
        $category11->setName("Thai");
        $manager->persist($category11);

        $category12 = new Category();
        $category12->setName("Fast Food");
        $manager->persist($category12);

        $category13 = new Category();
        $category13->setName("Coffee & Tea");
        $manager->persist($category13);

        $category14 = new Category();
        $category14->setName("Bakeries");
        $manager->persist($category14);

        $category15 = new Category();
        $category15->setName("Takeout");
        $manager->persist($category15);

        $category16 = new Category();
        $category16->setName("Home Services");
        $manager->persist($category16);

        $category17 = new Category();
        $category17->setName("Contractors");
        $manager->persist($category17);

        $category18 = new Category();
        $category18->setName("Electricians");
        $manager->persist($category18);

        $category19 = new Category();
        $category19->setName("Landscaping");
        $manager->persist($category19);

        $category20 = new Category();
        $category20->setName("Plumbers");
        $manager->persist($category20);

        $category21 = new Category();
        $category21->setName("Home Cleaners");
        $manager->persist($category21);

        $category22 = new Category();
        $category22->setName("Locksmiths");
        $manager->persist($category22);

        $category23 = new Category();
        $category23->setName("Movers");
        $manager->persist($category23);

        $category24 = new Category();
        $category24->setName("HVAC");
        $manager->persist($category24);

        $category25 = new Category();
        $category25->setName("Auto Services");
        $manager->persist($category25);

        $category26 = new Category();
        $category26->setName("Auto Repair");
        $manager->persist($category26);

        $category27 = new Category();
        $category27->setName("Car Dealers");
        $manager->persist($category27);

        $category28 = new Category();
        $category28->setName("Body Shops");
        $manager->persist($category28);

        $category29 = new Category();
        $category29->setName("Oil Change");
        $manager->persist($category29);

        $category30 = new Category();
        $category30->setName("Auto Detailing");
        $manager->persist($category30);

        $category31 = new Category();
        $category31->setName("Towing");
        $manager->persist($category31);

        $category32 = new Category();
        $category32->setName("Parking");
        $manager->persist($category32);

        $category33 = new Category();
        $category33->setName("Car Wash");
        $manager->persist($category33);

        $category34 = new Category();
        $category34->setName("Dry Cleaning");
        $manager->persist($category34);

        $category35 = new Category();
        $category35->setName("Hair Salons");
        $manager->persist($category35);

        $category36 = new Category();
        $category36->setName("Phone Repair");
        $manager->persist($category36);

        $category37 = new Category();
        $category37->setName("Gyms");
        $manager->persist($category37);

        $category38 = new Category();
        $category38->setName("Bars");
        $manager->persist($category38);

        $category39 = new Category();
        $category39->setName("Massages");
        $manager->persist($category39);

        $category40 = new Category();
        $category40->setName("Nightlife");
        $manager->persist($category40);

        $category41 = new Category();
        $category41->setName("Shopping");
        $manager->persist($category41);

        $category42 = new Category();
        $category42->setName("More");
        $manager->persist($category42);

        $manager->flush();

        $this->addReference("category", $category);
        $this->addReference("category2", $category2);
    }
}

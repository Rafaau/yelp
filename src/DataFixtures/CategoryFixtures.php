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
        $category->setName("Burgers");
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName("Restaurants");
        $manager->persist($category2);

        $manager->flush();

        $this->addReference("category", $category);
        $this->addReference("category2", $category2);
    }
}

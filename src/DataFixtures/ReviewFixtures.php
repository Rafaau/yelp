<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $review = new Review();
        $review->setContent("Considering that London city is one of the TOP cities when it comes to Burgers , I wasn't surprised that Patty and Bun would meet my expectations, simply their burgers are TOP !
        Juicy meat, fresh ingredients and YUMMY flavors.
        
        I tried the lamb shank burger , nothing is random here here . The cheese was Feta not a cheddar to meet the lamb meat in flavor. Tahini is the sauce not a mayo. And the boom here they add eggplant to the burger ! Yes !
        This what makes this sandwich one of the TOP
        Service is fast and friendly,ambiance is casual and simple .
        Burger to enjoy !");
        $review->setUser($this->getReference("user"));
        $review->setBusiness($this->getReference("business"));
        $manager->persist($review);

        $review2 = new Review();
        $review2->setContent("Very solid burger here. Cooked medium with pickled onions and a very tasty sauce. Messy as hell though and they didn't bring me a plate. Needed a lot of napkins.

        Went in for dinner and was quite hungry. Small place and was seated right away. Service is very speedy which is unusual in London. Ordered a side of chips which were rosemary salted and just ok and a local IPA with floral notes which I much enjoyed. If you are in a hurry this is your spot. I was in and out less than an hour.");
        $review2->setUser($this->getReference("user2"));
        $review2->setBusiness($this->getReference("business"));
        $manager->persist($review2);

        $review3 = new Review();
        $review3->setContent("The atmosphere of the restaurant is really warm and inviting. Really excellent, quick, and pleasant service. Dined in and ordered a Soda and The Hot Chick at Patty & Bun. Excellent way to start my day! Delicious cuisine and refreshing beverages. It is a fantastic location for brunch and breakfast.");
        $review3->setUser($this->getReference("user3"));
        $review3->setBusiness($this->getReference("business"));
        $manager->persist($review3);

        $manager->flush();

        $this->addReference("review", $review);
        $this->addReference("review2", $review2);
        $this->addReference("review3", $review3);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BusinessFixtures::class,
        ];
    }
}

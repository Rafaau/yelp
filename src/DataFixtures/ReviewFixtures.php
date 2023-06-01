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
        \nJuicy meat, fresh ingredients and YUMMY flavors.
        
        \nI tried the lamb shank burger , nothing is random here here . The cheese was Feta not a cheddar to meet the lamb meat in flavor. Tahini is the sauce not a mayo. And the boom here they add eggplant to the burger ! Yes !
        \nThis what makes this sandwich one of the TOP
        \nService is fast and friendly,ambiance is casual and simple .
        \nBurger to enjoy !");
        $review->setImages(
            [
                "build/images/review_1.2e79276d.jpg"
            ]
        );
        $review->setStars(8);
        $review->setUser($this->getReference("user"));
        $review->setBusiness($this->getReference("business"));
        $manager->persist($review);

        $review2 = new Review();
        $review2->setContent("Very solid burger here. Cooked medium with pickled onions and a very tasty sauce. Messy as hell though and they didn't bring me a plate. Needed a lot of napkins.
        \n
        \nWent in for dinner and was quite hungry. Small place and was seated right away. Service is very speedy which is unusual in London. Ordered a side of chips which were rosemary salted and just ok and a local IPA with floral notes which I much enjoyed. If you are in a hurry this is your spot. I was in and out less than an hour.");
        $review2->setImages(
            [
                "build/images/review_2.bc7f40d6.jpg"
            ]
        );
        $review2->setStars(9);
        $review2->setUser($this->getReference("user2"));
        $review2->setBusiness($this->getReference("business2"));
        $manager->persist($review2);

        $review3 = new Review();
        $review3->setContent("The atmosphere of the restaurant is really warm and inviting. Really excellent, quick, and pleasant service. Dined in and ordered a Soda and The Hot Chick at Patty & Bun. Excellent way to start my day! Delicious cuisine and refreshing beverages. It is a fantastic location for brunch and breakfast.");
        $review3->setImages(
            [
                "build/images/review_3.bea8e152.jpg"
            ]
        );
        $review3->setStars(10);
        $review3->setUser($this->getReference("user3"));
        $review3->setBusiness($this->getReference("business3"));
        $manager->persist($review3);

        $review4 = new Review();
        $review4->setContent("These guys pay for reviews. They pay dishonest people who are looking for those who will write a review, promising to pay them. And people don't get paid for reviews. They are so deceitful! In fact their service and food is disgusting. do not recommend at all!");
        $review4->setImages(
            [
                "build/images/review_4.5451221c.jpg"
            ]
        );
        $review4->setStars(2);
        $review4->setUser($this->getReference("user4"));
        $review4->setBusiness($this->getReference("business"));
        $manager->persist($review4);

        $review5 = new Review();
        $review5->setContent("Great place for burgers! Reasonable sized burgers of good quality with not so much fuzz.");
        $review5->setImages(
            [
                "build/images/review_5.ce399deb.jpg"
            ]
        );
        $review5->setStars(6);
        $review5->setUser($this->getReference("user5"));
        $review5->setBusiness($this->getReference("business2"));
        $manager->persist($review5);

        $review6 = new Review();
        $review6->setContent("We take our Burgers very seriously! These Honest Burgers didn't disappoint.
        \nNo frills, just delicious yummy tasting burgers!!!");
        $review6->setImages(
            [
                "build/images/review_6.d440bdd7.jpg"
            ]
        );
        $review6->setStars(10);
        $review6->setUser($this->getReference("user6"));
        $review6->setBusiness($this->getReference("business3"));
        $manager->persist($review6);

        $review7 = new Review();
        $review7->setContent("Great quality and a reasonably priced alternative to the plethora of eateries in the vicinity of the South Kensington Underground Station.");
        $review7->setImages(
            [
                "build/images/review_7.2a95d2fb.jpg"
            ]
        );
        $review7->setStars(8);
        $review7->setUser($this->getReference("user7"));
        $review7->setBusiness($this->getReference("business"));
        $manager->persist($review7);

        $review8 = new Review();
        $review8->setContent("Now that I'm an expat, there are certain things from the US I didn't know I was spoiled with, such as the Beyond Burger. I took these things for granted in the US because Beyond Burger is hard to find in the UK...
        \n
        \nHonest Burger offers a 100% vegan burger made with a Beyond Meat patty, vegan cheese, and vegan mayo...it's pretty delicious (see picture). HB did a good job combining flavors and cooking the patty.
        \n
        \nHB also offers another burger that can be made vegan upon request (I think it was a falafel patty)...but I likely won't try it since I'm going there for a taste of home (the Beyond Patty!). You can't beat it.");
        $review8->setImages(
            [
                "build/images/review_8.56ed5374.jpg"
            ]
        );
        $review8->setStars(7);
        $review8->setUser($this->getReference("user8"));
        $review8->setBusiness($this->getReference("business2"));
        $manager->persist($review8);

        $manager->flush();

        $this->addReference("review", $review);
        $this->addReference("review2", $review2);
        $this->addReference("review3", $review3);
        $this->addReference("review4", $review4);
        $this->addReference("review5", $review5);
        $this->addReference("review6", $review6);
        $this->addReference("review7", $review7);
        $this->addReference("review8", $review8);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BusinessFixtures::class,
        ];
    }
}

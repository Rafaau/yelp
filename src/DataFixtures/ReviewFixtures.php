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
                "build/images/review_1.2e79276d.jpg",
                "build/images/review_4.5451221c.jpg"
            ]
        );
        $review->setStars(8);
        $review->setUser($this->getReference("user"));
        $review->setBusiness($this->getReference("business"));
        $review->setReactions(
            [
                "useful" => 11,
                "funny" => 20,
                "cool" => 8
            ]
        );
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
        $review2->setReactions(
            [
                "useful" => 42,
                "funny" => 11,
                "cool" => 20
            ]
        );
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
        $review3->setReactions(
            [
                "useful" => 2,
                "funny" => 1,
                "cool" => 0
            ]
        );
        $manager->persist($review3);

        $review4 = new Review();
        $review4->setContent("These guys pay for reviews. They pay dishonest people who are looking for those who will write a review, promising to pay them. And people don't get paid for reviews. They are so deceitful! In fact their service and food is disgusting. do not recommend at all!");
        $review4->setStars(2);
        $review4->setUser($this->getReference("user4"));
        $review4->setBusiness($this->getReference("business"));
        $review4->setReactions(
            [
                "useful" => 0,
                "funny" => 0,
                "cool" => 0
            ]
        );
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
        $review5->setReactions(
            [
                "useful" => 14,
                "funny" => 5,
                "cool" => 0
            ]
        );
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
        $review6->setReactions(
            [
                "useful" => 11,
                "funny" => 20,
                "cool" => 8
            ]
        );
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
        $review7->setReactions(
            [
                "useful" => 30,
                "funny" => 16,
                "cool" => 14
            ]
        );
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
        $review8->setReactions(
            [
                "useful" => 5,
                "funny" => 2,
                "cool" => 1
            ]
        );
        $manager->persist($review8);

        $review9 = new Review();
        $review9->setContent("It was rated best no 1 in the uk not sure why but so i got to try it because i saw the ads. Tried it and it was good to be honest but it was just small and not worth the price. Paying more than £10 for such a small burger that wont make you full its not worth it. Not recommended not worth to go but nonetheless the taste is good. Cheers! Hope this helps!");
        $review9->setImages(
            [
                "build/images/review_9.29cb9403.jpg"
            ]
        );
        $review9->setStars(6);
        $review9->setUser($this->getReference("user9"));
        $review9->setBusiness($this->getReference("business3"));
        $review9->setReactions(
            [
                "useful" => 2,
                "funny" => 0,
                "cool" => 0
            ]
        );
        $manager->persist($review9);

        $review10 = new Review();
        $review10->setContent("I can't quite bring myself to join the general chorus of praise for Bleecker. It may be beloved by Instagram and many London-based food writers whom I tend to trust, but for me, this place doesn't live up to the Ludwig-filtered hype.
        \n
        Don't get me wrong, these chaps serve up a pretty good burger. Juicy, well-seasoned, lifted by the crowd-pleasing ying-and-yang of cheese and burger sauce. But in London's ever-crowded burger space, that standard should be the minimum expectation.
        \n
        For me, their single-patty cheeseburger is a tad small. I know we should all be trying to reduce our consumption of red meat, but this lacked the satisfying heft of a good burger. You could, of course, double-up, but at £9.50, once you've thrown in a portion of fries and something to drink, that's pushing you over the £15 mark. Call me a miser, but that's too much for what is still just a simple street-food lunch.  
        \n
        My other gripe is the lack of variation on offer. I'm a firm believer in restaurants offering short menus of dishes they've mastered, but I'd still like more choices for toppings/additions than just bacon and blue-cheese.");
        $review10->setStars(6);
        $review10->setUser($this->getReference("user10"));
        $review10->setBusiness($this->getReference("business"));
        $review10->setReactions(
            [
                "useful" => 21,
                "funny" => 6,
                "cool" => 5
            ]
        );
        $manager->persist($review10);

        $review11 = new Review();
        $review11->setContent("Best. Burgers. In. London. That's done.
        \n
        Seriously, the Patty Bun (with black pudding sandwiched inbetween tow beef patties) is the best burger there is to eat this side of the Atlantic.
        \n
        So, SO good.");
        $review11->setImages(
            [
                "build/images/review_11.3417da81.jpg",
                "build/images/review_10.0001d14c.jpg"
            ]
        );
        $review11->setStars(10);
        $review11->setUser($this->getReference("user6"));
        $review11->setBusiness($this->getReference("business2"));
        $review11->setReactions(
            [
                "useful" => 18,
                "funny" => 4,
                "cool" => 2
            ]
        );
        $manager->persist($review11);

        $review12 = new Review();
        $review12->setContent("Two words : THE BEST !!!!!
        \n
        For a long time I considered Patty & Bun's Smokey Robinson burger as the best burger in London... but this was before I went to Bleecker St in Spitafields market.
        The Black Burger has two layers of beef with black pudding, cheese and onions in the middle. The beef is perfectly cooked and it is tasty and juicy. The bun is very good and moist.
        \n
        WARNING : You should not walk passed Bleecker St. if you are on a diet as you won't resist temptation.");
        $review12->setImages(
            [
                "build/images/review_12.0b293c52.jpg"
            ]
        );
        $review12->setStars(10);
        $review12->setUser($this->getReference("user5"));
        $review12->setBusiness($this->getReference("business3"));
        $review12->setReactions(
            [
                "useful" => 10,
                "funny" => 6,
                "cool" => 1
            ]
        );
        $manager->persist($review12);

        $review13 = new Review();
        $review13->setContent("*Reviewing veg options*
        \n
        Oh, woe to high expectations! After hearing and reading so much about Bleecker St. burgers, I thought they might offer a decent veg burger. Sadly, this is not the case and I'm afraid I will not be back again unless the menu changes.
        \n
        Perhaps I should give them a little bit of credit given that they gained fame as a food truck. And OK, I know it's a struggle for burger places to get the right balance of street accessibility and flavour when it comes to a non-meat patty, but, come on, be a bit more adventurous. Two crisp tofu squares garnished with a weak piece of lettuce and squirted with some hot sauce I could buy in any shop is a tad disappointing. And at £6!! Eep. There are other places that actually take lentils or beans or mushrooms and cook something for a fiver. And I hate mushrooms...
        \n
        The three stars is for the tofu being good quality and the fries being tasty enough. The sweet potato fries are nice and absolutely satisfied my craving for them, but they were a little too moist for me and way too expensive (£4). We also tried the regular fries, which were quite nice, but doesn't blow my mind away enough to bring me back.
        \n
        I understand that vege-/pescetarians are just not a priority market for burger joints, but there are times I crave a comforting, hearty veg burger and give some a try. Sadly, though I would have loved to give this stand my support, I won't be back for the tofu burger again.");
        $review13->setImages(
            [
                "build/images/review_13.fa33b016.jpg"
            ]
        );
        $review13->setStars(7);
        $review13->setUser($this->getReference("user8"));
        $review13->setBusiness($this->getReference("business"));
        $review13->setReactions(
            [
                "useful" => 4,
                "funny" => 0,
                "cool" => 1
            ]
        );
        $manager->persist($review13);

        $manager->flush();

        $this->addReference("review", $review);
        $this->addReference("review2", $review2);
        $this->addReference("review3", $review3);
        $this->addReference("review4", $review4);
        $this->addReference("review5", $review5);
        $this->addReference("review6", $review6);
        $this->addReference("review7", $review7);
        $this->addReference("review8", $review8);
        $this->addReference("review9", $review9);
        $this->addReference("review10", $review10);
        $this->addReference("review11", $review11);
        $this->addReference("review12", $review12);
        $this->addReference("review13", $review13);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BusinessFixtures::class,
        ];
    }
}

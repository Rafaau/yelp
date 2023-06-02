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
        $user->setThingsILove("traveling, skiing, good company and good food!");
        $user->setMyHometown("Albuquerque, United States");
        $user->setDescription("When I see food, I eat it.");
        $user->setWhenIAmNotWhelping("I'm thinking about my next meal");
        $user->setWhyYouShouldRead("You and I have something in common - good eats :)");
        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername("Allison E.");
        $user2->setUserImage("build/images/avatar_2.0a664683.jpg");
        $user2->setAddress("Santa Barbara, United States");
        $user2->setThingsILove("Playing handball, lifting, snowboarding, singing, piano, nail art, blogging, doodling, artsy farty stuff, video games, and EATING");
        $user2->setDescription("You gonna eat that?");
        $user2->setWhenIAmNotWhelping("I'm painting my nails");
        $user2->setWhyYouShouldRead("I try my best to be as descriptive as possible and take lots of pictures!");
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername("Morgan R.");
        $user3->setUserImage("build/images/avatar_3.662404f0.jpg");
        $user3->setAddress("Lincoln, United States");
        $user3->setThingsILove("food, NYC, travel in that order!");
        $user3->setMyHometown("Ex-New Yorker now in San Francisco");
        $user3->setDescription("Om nom nom nom");
        $user3->setWhenIAmNotWhelping("crunching numbers at a financial firm");
        $user3->setWhyYouShouldRead("I know food, am candid (like when I'm saying this) and fun, to say the least! :D");
        $manager->persist($user3);

        $user4 = new User();
        $user4->setUsername("Pedro B.");
        $user4->setUserImage("build/images/avatar_4.553187e6.jpg");
        $user4->setAddress("Chicago, United States");
        $user4->setThingsILove("eating everything, traveling, reading and playing board games");
        $user4->setMyHometown("Arcadia, CA");
        $manager->persist($user4);

        $user5 = new User();
        $user5->setUsername("Ti V.");
        $user5->setUserImage("build/images/avatar_5.db9d8650.jpg");
        $user5->setAddress("Baltimore, United States");
        $user5->setThingsILove("Traveling (been to 6 out of 7 continents)..and I'm always on the look out for good food.");
        $user5->setMyHometown("San Francisco, California");
        $user5->setDescription("I'm handing out Yelp reviews and cupcakes, and I'm all out of cupcakes...");
        $user5->setWhenIAmNotWhelping("I'm daydreaming about Michelle Pfeffier");
        $user5->setWhyYouShouldRead("If you're bored");
        $manager->persist($user5);

        $user6 = new User();
        $user6->setUsername("Pete B.");
        $user6->setUserImage("build/images/avatar_6.134f3f2f.jpg");
        $user6->setAddress("San Francisco, United States");
        $user6->setThingsILove("my family, food in general, traveling, exploring, photography, being outdoors, journal entries, cooking, etc.");
        $user6->setMyHometown("Boston, MA");
        $user6->setDescription("Eating until poor.");
        $user6->setWhenIAmNotWhelping("I'm traveling, cooking, photographing, or eating some more");
        $user6->setWhyYouShouldRead("Food lover. Avid traveler. Cook. Those are your reasons why!");
        $manager->persist($user6);

        $user7 = new User();
        $user7->setUsername("Thomas M.");
        $user7->setUserImage("build/images/avatar_7.3e9e5bae.jpg");
        $user7->setAddress("MD, United States");
        $user7->setMyHometown("Roseville, CA");
        $user7->setDescription("Vegan - Coffee - Travel");
        $user7->setWhenIAmNotWhelping("I'm never not yelping, let's be honest.");
        $user7->setWhyYouShouldRead("I go fun places");
        $manager->persist($user7);

        $user8 = new User();
        $user8->setUsername("Motty S.");
        $user8->setUserImage("build/images/avatar_8.55b0d884.jpg");
        $user8->setAddress("San Francisco Bay Area, United States");
        $user8->setThingsILove("Friends, Italy, Boston, Doodles");
        $user8->setMyHometown("Boston, Ma");
        $user8->setDescription("I Can't Wait for Today!");
        $user8->setWhenIAmNotWhelping("I'm at the beach");
        $manager->persist($user8);

        $user9 = new User();
        $user9->setUsername("Faisal S.");
        $user9->setUserImage("build/images/avatar_9.2222d5b9.jpg");
        $user9->setAddress("Scotts Valley, United States");
        $user9->setThingsILove("Mrs. K.");
        $user9->setDescription("Wave your hands in the air and Yelp like you just don't care!");
        $user9->setWhenIAmNotWhelping("Yeah, like that ever happens.");
        $user9->setWhyYouShouldRead('"Lets eat, Grandma" or "Lets eat Grandma". Punctuation saves lives!');
        $manager->persist($user9);

        $user10 = new User();
        $user10->setUsername("Lauren V.");
        $user10->setUserImage("build/images/avatar_10.257cf234.jpg");
        $user10->setAddress("Colorado Springs, United States");
        $user10->setThingsILove("Disneyworld, Dogs, New York City and the North Fork of Long Island");
        $user10->setMyHometown("Brooklyn NY");
        $user10->setDescription("Happiness is a state of mind. It's just according to the way you look at things - Walt Disney");
        $user10->setWhyYouShouldRead("Because I put you in the circle of trust");
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

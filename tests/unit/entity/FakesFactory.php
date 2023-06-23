<?php

namespace App\Tests\unit\entity;

use App\Entity\Business;
use App\Entity\Category;
use App\Entity\Message;
use App\Entity\Notification;
use App\Entity\Review;
use App\Entity\User;
use Faker\Factory;

class FakesFactory {
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function generateUser(): User
    {
        $user = new User();

        $user->setEmail($this->faker->email());
        $user->setPassword($this->faker->password());
        $user->setUsername($this->faker->username());
        $user->setAddress($this->faker->address());
        $user->setThingsILove($this->faker->text());
        $user->setMyHometown($this->faker->city());
        $user->setDescription($this->faker->text());
        $user->setWhenIAmNotWhelping($this->faker->text());
        $user->setWhyYouShouldRead($this->faker->text());

        return $user;
    }

    public function generateInvalidUser(): User
    {
        $user = new User();

        $user->setPassword($this->faker->password());
        $user->setUsername($this->faker->username());
        $user->setAddress($this->faker->address());
        $user->setThingsILove($this->faker->text());
        $user->setMyHometown($this->faker->city());
        $user->setDescription($this->faker->text());
        $user->setWhenIAmNotWhelping($this->faker->text());
        $user->setWhyYouShouldRead($this->faker->text());

        return $user;
    }

    public function generateBusiness()
    {
        $business = new Business();
        $business->setName($this->faker->company());
        $business->setLocation($this->faker->city());
        $business->setAddress($this->faker->address());
        $business->setWebsite($this->faker->url());
        $business->setPhoneNumber($this->faker->phoneNumber());
        $business->setOwner($this->faker->name());
        $business->setDescription($this->faker->text());
        $business->setExpensiveness(1);
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
        
        return $business;
    }

    public function generateCategory()
    {
        $category = new Category();
        $category->setName($this->faker->word());

        return $category;
    }

    public function generateInvalidBusiness()
    {
        $business = new Business();
        $business->setLocation($this->faker->city());
        $business->setAddress($this->faker->address());
        $business->setWebsite($this->faker->url());
        $business->setPhoneNumber($this->faker->phoneNumber());
        $business->setOwner($this->faker->name());
        $business->setDescription($this->faker->text());
        $business->setExpensiveness(1);
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
        
        return $business;
    }

    public function generateReview(): Review
    {
        $review = new Review();
        $review->setContent($this->faker->text());
        $review->setStars($this->faker->numberBetween(1, 10));
        $review->setReactions(
            [
                "useful" => $this->faker->numberBetween(1, 50),
                "funny" => $this->faker->numberBetween(1, 50),
                "cool" => $this->faker->numberBetween(1, 50),
            ]
        );

        return $review;
    }

    public function generateInvalidReview(): Review
    {
        $review = new Review();
        $review->setContent($this->faker->text());
        $review->setReactions(
            [
                "useful" => $this->faker->numberBetween(1, 50),
                "funny" => $this->faker->numberBetween(1, 50),
                "cool" => $this->faker->numberBetween(1, 50),
            ]
        );

        return $review;
    }

    public function generateMessage(): Message
    {
        $message = new Message();
        $message->setContent($this->faker->text());

        return $message;
    }

    public function generateInvalidMessage(): Message
    {
        $message = new Message();

        return $message;
    }

    public function generateNotification(): Notification
    {
        $notification = new Notification();
        $notification->setTitle($this->faker->name());
        $notification->setMessage($this->faker->text());

        return $notification;
    }

    public function generateInvalidNotification(): Notification
    {
        $notification = new Notification();
        $notification->setMessage($this->faker->text());

        return $notification;
    }
}

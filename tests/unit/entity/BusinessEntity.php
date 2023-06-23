<?php


namespace App\Tests\unit\entity;

use App\Entity\Business;
use App\Entity\Category;
use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BusinessEntity extends KernelTestCase
{
    /** @var EntityManagerInterface */
    private $entityManager;
    private $fakesFactory;
    private $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->fakesFactory = new FakesFactory();
        $this->validator = static::getContainer()->get('validator');

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /** @test */
    public function a_business_can_be_created_in_the_database()
    {
        // Arrange
        $business = $this->fakesFactory->generateBusiness();
        $category = $this->fakesFactory->generateCategory();
        $this->entityManager->persist($category);
        $business->addCategory($category);

        // Act
        $this->entityManager->persist($business);
        $this->entityManager->flush();

        $businessRepository = $this->entityManager->getRepository(Business::class);
        $businessFromDatabase = $businessRepository->findOneBy(['name' => $business->getName()]);

        // Assert
        $this->assertEquals($business->getName(), $businessFromDatabase->getName());
        $this->assertEquals($business->getLocation(), $businessFromDatabase->getLocation());
        $this->assertEquals($business->getAddress(), $businessFromDatabase->getAddress());
        $this->assertEquals($business->getWebsite(), $businessFromDatabase->getWebsite());
        $this->assertEquals($business->getPhoneNumber(), $businessFromDatabase->getPhoneNumber());
        $this->assertEquals($business->getOwner(), $businessFromDatabase->getOwner());
        $this->assertEquals($business->getDescription(), $businessFromDatabase->getDescription());
        $this->assertEquals($business->getExpensiveness(), $businessFromDatabase->getExpensiveness());
        $this->assertEquals($business->getCategories(), $businessFromDatabase->getCategories());
    }

    /** @test */
    public function it_throws_an_error_when_name_is_missing()
    {
        // Arrange
        $business = $this->fakesFactory->generateInvalidBusiness();

        // Act
        $errors = $this->validator->validate($business);

        // Assert
        $this->assertGreaterThan(0, count($errors), "No validation error for missing name");
        $this->assertStringContainsString('Name is required', (string)$errors);
    }
}
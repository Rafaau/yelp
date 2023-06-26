<?php

namespace App\Tests\Unit\Service;

use App\Entity\Business;
use App\Entity\Category;
use App\Repository\BusinessRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use App\Service\BusinessService as ServiceBusinessService;

class BusinessService extends TestCase
{
    private $entityManager;
    private $categoryRepository;
    private $businessRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->businessRepository = $this->createMock(BusinessRepository::class);
    }

    public function tearDown(): void
    {
        $this->entityManager = null;
        $this->categoryRepository = null;
        $this->businessRepository = null;
    }

    /** @test */
    public function should_return_business_when_data_is_valid()
    {
        $data = [
            'name' => 'testName',
            'location' => 'testLocation',
            'description' => 'testDescription',
            'website' => 'testWebsite',
            'phone' => 'testPhone',
            'address' => 'testAddress',
            'hours' => 'testMon,testTue,testWed,testThu,testFri,testSat,testSun',
            'categories' => 'testCat1,testCat2',
        ];
        $owner = 'testOwner';

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Business::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->categoryRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->withConsecutive([['name' => 'testCat1']], [['name' => 'testCat2']])
            ->willReturnOnConsecutiveCalls(new Category(), new Category());

        $businessService = new ServiceBusinessService($this->entityManager, $this->categoryRepository, $this->businessRepository);

        $business = $businessService->createBusiness($data, $owner);

        $this->assertInstanceOf(Business::class, $business);
        $this->assertEquals($data['name'], $business->getName());
        $this->assertEquals($data['location'], $business->getLocation());
    }
}
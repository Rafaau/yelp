<?php

namespace App\Service;

use App\Entity\Business;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\BusinessServiceInterface;

class BusinessService implements BusinessServiceInterface
{
    private EntityManagerInterface $entityManager;
    private CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    public function createBusiness(array $data, string $owner): Business
    {
        $business = new Business();

        $business->setName($data['name']);
        $business->setLocation($data['location']);
        $business->setDescription($data['description']);
        $business->setWebsite($data['website']);
        $business->setPhoneNumber($data['phone']);
        $business->setAddress($data['address']);
        $business->setOwner($owner);
        $hours = explode(',', $data['hours']);
        $business->setHours(
            [
                'Mon' => $hours[0],
                'Tue' => $hours[1],
                'Wed' => $hours[2],
                'Thu' => $hours[3],
                'Fri' => $hours[4],
                'Sat' => $hours[5],
                'Sun' => $hours[6],
            ]
        );
        $categories = explode(',', $data['categories']);
        foreach ($categories as $categoryName) {
            $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);
            if ($category) {     
                $business->addCategory($category);
            }
        }
        $business->setExpensiveness(1);

        $this->entityManager->persist($business);
        $this->entityManager->flush();

        return $business;
    }
}

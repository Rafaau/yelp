<?php

namespace App\Interface;

use App\Entity\Business;

interface BusinessServiceInterface
{
    public function createBusiness(array $data, string $owner): Business;
    public function getBusinesses(string $cflt, string $find_loc): array;
    public function getBusiness(string $business, string $location): array;
}

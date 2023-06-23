<?php

namespace App\Interface;

use App\Entity\Business;

interface BusinessServiceInterface
{
    public function createBusiness(array $data, string $owner): Business;
}

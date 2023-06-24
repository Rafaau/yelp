<?php

namespace App\Interface;

use App\Entity\Review;

interface ReviewServiceInterface
{
    public function postReview($data, $user): Review;
    public function updateReactions($data): void;
    public function deleteReview(int $id): void;
    public function getReviews(string $location, int $page, int $limit): array;
}
<?php

declare(strict_types=1);

namespace App\Services\Review;

use App\Models\Review\Review;

class ReviewService
{
    public function create(int $productVariantId, string $title, string $body, int $rating): Review
    {
        $review = Review::create([
            'title'              => $title,
            'body'               => $body,
            'rating'             => $rating,
            'product_variant_id' => $productVariantId,
            'user_id'            => auth()->user()->id,
        ]);
        $review->save();

        return $review;
    }

    public function delete(int $id): bool
    {
        return Review::find($id)->delete();
    }

    public function approve(int $id): bool
    {
        return Review::find($id)->approve();
    }

    public function archive(int $id): bool
    {
        return Review::find($id)->archive();
    }
}

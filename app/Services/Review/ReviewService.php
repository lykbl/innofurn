<?php

declare(strict_types=1);

namespace App\Services\Review;

use App\GraphQL\Queries\Review\ReviewOrderByEnum;
use App\Models\Review\Review;
use App\Models\User;
use Laravel\Scout\Builder;
use Lunar\Models\Language;
use Meilisearch\Contracts\SearchQuery;
use Meilisearch\Endpoints\Indexes;

class ReviewService
{
    public function create(int $userId, int $productVariantId, string $title, string $body, int $rating): Review
    {
        $review = Review::create([
            'title'              => $title,
            'body'               => $body,
            'rating'             => $rating,
            'product_variant_id' => $productVariantId,
            'user_id'            => $userId,
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

    public function searchProductReviews(
        User $user,
        array $filters,
        int $page = 1,
        int $perPage = 10,
        ReviewOrderByEnum $orderBy = ReviewOrderByEnum::RATING_DESC,
    ): Builder {
        $langCode           = $user->retailCustomer()->language->code ?? Language::getDefault()->code;
        $productId          = $filters['productId'] ?? null;
        $search             = $filters['search'] ?? '';
        $meiliSearchFilters = [
            "product_id = $productId",
        ];
        if ($ratingFilter = $filters['rating'] ?? null) {
            $meiliSearchFilters[] = "rating = $ratingFilter";
        }
        $meiliSearchOrderBy = ["{$orderBy->key()}:{$orderBy->direction()}"];

        return Review::search($search, function (Indexes $meiliSearch, string $search, array $baseOptions) use ($page, $perPage, $meiliSearchFilters, $meiliSearchOrderBy) {
            $searchQuery = new SearchQuery();
            $searchQuery->setQuery($search)
                ->setFilter($meiliSearchFilters)
                ->setPage($page ?? $baseOptions['page'] ?? 1)
                ->setHitsPerPage($perPage ?? $baseOptions['hitsPerPage'] ?? 25)
                ->setSort($meiliSearchOrderBy)
            ;

            return $meiliSearch->search($search, searchParams: $searchQuery->toArray());
        })->within("reviews_index_$langCode");
    }
}

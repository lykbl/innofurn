<?php

declare(strict_types=1);

namespace App\Services;

use App\GraphQL\Exceptions\Product\ProductNotFoundException;
use App\Models\Product;
use App\Models\Url;

class ProductService
{
    // TODO make to gql only?
    public function findBySlug(string $slug): Product
    {
        $url = Url::firstWhere(['slug' => $slug, 'element_type' => \Lunar\Models\Product::class]);
        if (!$url) {
            throw new ProductNotFoundException();
        }

        return $url->element;
    }
}

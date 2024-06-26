<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\FieldTypes\ColorFieldType;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\TranslatedText;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()
            ->count(30)
            ->has(
                ProductVariant::factory()->count(fake()->numberBetween(1))->state(function (array $attributes, $model) {
                    return [
                        'attribute_data' => [
                            'name'        => new TranslatedText(['en' => 'Chair '.fake()->word, 'es' => 'Chair '.fake()->word]),
                            'description' => new TranslatedText(['en' => 'Chair '.fake()->sentence, 'es' => 'Chair '.fake()->sentence]),
                            'color'       => new ColorFieldType(['value' => fake()->hexColor, 'label' => fake()->word]),
                            'material'    => new TranslatedText(['en' => fake()->word, 'es' => fake()->word]),
                        ],
                    ];
                })->has(
                    Price::factory()->count(1)->state(function ($attributes, $model) {
                        return [
                            'currency_id' => 1,
                        ];
                    }),
                    'prices',
                ),
                'variants'
            )->afterCreating(function ($product): void {
                $product->addMediaFromUrl(fake()->imageUrl)->toMediaCollection('images');
            })
            ->create([
                'product_type_id' => 6,
                'status'          => 'published',
                'brand_id'        => 2,
                'attribute_data'  => [
                    'name'        => new Text('Chair '.fake()->word),
                    'description' => new Text('Chair '.fake()->sentence),
                ],
            ]);
    }
}

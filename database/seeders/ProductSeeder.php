<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Product\Product;
use App\Domains\ProductVariant\ProductVariant;
use App\FieldTypes\ColorFieldType;
use App\Models\Price;
use Illuminate\Database\Seeder;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\TranslatedText;
use Lunar\Models\ProductType;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // collection
        Product::factory()
            ->count(30)
            ->has(
                ProductVariant::factory()->count(fake()->numberBetween(3, 5))->state(function (array $attributes, $model) {
                    return [
                        'attribute_data' => [
                            'name' => new TranslatedText(['en' => 'Chair ' . fake()->word, 'es' => 'Chair '. fake()->word]),
                            'description' => new TranslatedText(['en' => 'Chair ' . fake()->sentence, 'es' => 'Chair '. fake()->sentence]),
                            'color' => new ColorFieldType(['value' => fake()->hexColor, 'label' => fake()->word]),
                            'material' => new TranslatedText(['en' => fake()->word, 'es' => fake()->word]),
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
            )
            ->create([
                'product_type_id' => 6,
                'attribute_data' => [
                    'name' => new Text('Chair ' . fake()->word),
                    'description' => new Text('Chair ' . fake()->sentence),
                ],
            ]);
    }
}

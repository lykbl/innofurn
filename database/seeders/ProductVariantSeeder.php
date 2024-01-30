<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\ProductVariant\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $howManyMillions = 100;
        for ($i = 0; $i < $howManyMillions; ++$i) {
            $seeds = array_fill(0, 10 ** 6, [
                'product_id'   => 1,
                'tax_class_id' => 1,
            ]);
            $chunks = array_chunk($seeds, 30000);
            foreach ($chunks as $chunk) {
                ProductVariant::insert($chunk);
            }
        }
    }
}

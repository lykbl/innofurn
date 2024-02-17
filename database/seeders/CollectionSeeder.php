<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Lunar\FieldTypes\TranslatedText;

class CollectionSeeder extends Seeder
{
    private const MAIN_COLLECTION_GROUP_ID = 1;

    public function run(): void
    {
        $collections = [
            'Furniture' => [
                'Chairs' => [
                    'Dining Chairs' => [],
                    'Desk Chairs' => [],
                    'Cafe Chairs' => [],
                ],
                'Beds' => [
                    'Single Beds' => [],
                    'Double Beds' => [],
                    'Sofa Beds' => [],
                ],
                'Tables' => [
                    'Dining Tables' => [],
                    'Coffee Tables' => [],
                    'Cafe Tables' => [],
                ],
                'Wardrobes' => [
                    'Corner Wardrobes' => [],
                    'Sliding Wardrobes' => [],
                    'Solitaire Wardrobes' => [],
                ],
            ],
            'Pots & Plants' => [
                'Flowers' => [],
                'Flower Pots' => [],
                'Watering Cans' => [],
            ],
        ];

        $this->createCollectionWithChildren($collections);
    }

    private function createCollectionWithChildren(array $root, ?int $parentId = null)
    {
        foreach ($root as $name => $children) {
            $id = DB::table('lunar_collections')->insertGetId([
                'parent_id' => $parentId,
                'collection_group_id' => self::MAIN_COLLECTION_GROUP_ID,
                'type' => 'static',
                'attribute_data' => json_encode([
                    'name' => [
                        'value' => ['en' => $name],
                        'field_type' => TranslatedText::class,
                    ],
                ]),
            ]);
            $this->createCollectionWithChildren($children, $id);
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category\Category as CategoryModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RecursiveArrayIterator;

class Category extends Seeder
{
    private const CATEGORIES = [
        ['name' => 'Decorations', 'children' => [
            ['name' => 'Wall Décor', 'children' => [
                ['name' => 'Picture & photo frames'],
                ['name' => 'Ready to hang art'],
                ['name' => 'Posters'],
                ['name' => 'Wall accents'],
            ]],
        ]],
        ['name' => 'Plants and flowers', 'children' => [
            ['name' => 'Plants'],
            ['name' => 'Dried flowers'],
            ['name' => 'Growing accessories'],
        ]],
    ];

    public function run(): void
    {
        $categories = self::CATEGORIES;

        foreach ($categories as $parentCategory) {
            $this->createCategory(name: $parentCategory['name'], children: $parentCategory['children'] ?? []);
        }
    }

    private function createCategory(string $name, ?int $parent = null, array $children = [])
    {
//        $model = CategoryModel::create(['name' => $name, 'parent' => $parent]);
//        $model->save();
//
//        if (count($children)) {
//            foreach ($children as $child) {
//                $this->createCategory($child['name'], $model->id, $child['children'] ?? []);
//            }
//        }
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Lunar\Models\Collection as BaseCollection;

class Collection extends BaseCollection implements Translatable
{
    public static function recursiveChildrenQuery(): Builder
    {
        $recursiveChildrenQuery = DB::table('lunar_collections', 'root')
            ->select(['root.*', DB::raw('1 as depth')])
            ->join('lunar_collection_product', 'root.id', '=', 'lunar_collection_product.collection_id')
            ->unionAll(
                DB::table('lunar_collections', 'children')
                    ->select(['children.*', DB::raw('next_level.depth + 1')])
                    ->join('recursive_hierarchy as next_level', 'next_level.id', '=', 'children.parent_id')
            )
        ;

        return $recursiveChildrenQuery;
    }

    public static function recursiveParentsQuery(): Builder
    {
        $recursiveParentsQuery = DB::table('lunar_collections', 'root')
            ->select(['root.*', DB::raw('1 as depth')])
            ->join('lunar_collection_product', 'root.id', '=', 'lunar_collection_product.collection_id')
            ->unionAll(
                DB::table('lunar_collections', 'parents')
                    ->select(['parents.*', DB::raw('prev_level.depth + 1')])
                    ->join('recursive_hierarchy as prev_level', 'prev_level.parent_id', '=', 'parents.id')
            );

        return $recursiveParentsQuery;
    }
}

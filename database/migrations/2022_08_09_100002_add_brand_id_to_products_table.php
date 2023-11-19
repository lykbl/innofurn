<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;
use Lunar\Facades\DB;

class AddBrandIdToProductsTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'products', function (Blueprint $table): void {
            $table->foreignId('brand_id')->after('id')
                ->nullable()
                ->constrained($this->prefix.'brands');
        });

        Schema::table($this->prefix.'products', function (Blueprint $table): void {
            $table->dropColumn('brand');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'products', function ($table): void {
            if ('sqlite' !== DB::getDriverName()) {
                $table->dropForeign(['brand_id']);
            }
            $table->dropColumn('brand_id');
        });
    }
}

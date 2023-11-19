<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddPositionToMediaProductVariantTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'media_product_variant', function (Blueprint $table): void {
            $table->smallInteger('position')->after('primary')->default(1)->index();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'media_product_variant', function (Blueprint $table): void {
            $table->dropColumn('position');
        });
    }
}

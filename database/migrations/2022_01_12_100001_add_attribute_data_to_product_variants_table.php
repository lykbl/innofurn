<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddAttributeDataToProductVariantsTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'product_variants', function (Blueprint $table): void {
            $table->json('attribute_data')->after('tax_class_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'product_variants', function (Blueprint $table): void {
            $table->dropColumn('attribute_data');
        });
    }
}

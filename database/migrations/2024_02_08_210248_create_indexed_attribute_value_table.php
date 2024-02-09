<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('indexed_product_attribute_values', function (Blueprint $table): void {
            $table->string('value');
            $table->string('type');
            $table->foreignId('attributable_id')->constrained('lunar_attributables');
            $table->foreignId('product_type_id')->constrained('lunar_product_types');
            $table->string('language_code', 2)->default('');
        });

        Schema::table('indexed_product_attribute_values', function (Blueprint $table): void {
            $table->primary([
                'value',
                'attributable_id',
                'product_type_id',
                'language_code',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indexed_product_attribute_values');
    }
};

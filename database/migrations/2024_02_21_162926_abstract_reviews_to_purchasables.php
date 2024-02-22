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
        Schema::table('reviews', function (Blueprint $table): void {
            $table->dropForeign('reviews_product_variant_id_foreign');
            $table->renameColumn('product_variant_id', 'reviewable_id');
            $table->string('reviewable_type')->default('Lunar\\\\Models\\\\ProductVariant');
        });

        Schema::table('reviews', function (Blueprint $table): void {
            $table->string('reviewable_type')->default(null)->change();
        });

        Schema::table('lunar_product_variants', function (Blueprint $table): void {
            $table->addColumn('float', 'average_rating')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table): void {
            $table->renameColumn('reviewable_id', 'product_variant_id');
            $table->dropColumn('reviewable_type');
            $table->foreign('product_variant_id')->references('id')->on('lunar_product_variants');
        });

        Schema::table('lunar_product_variants', function (Blueprint $table): void {
            $table->dropColumn('average_rating');
        });
    }
};

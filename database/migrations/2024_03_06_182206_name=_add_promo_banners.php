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
        Schema::create('lunar_promotion_banners', function (Blueprint $table): void {
            $table->id();
            $table->json('attribute_data');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('discount_id')->constrained('lunar_discounts')->cascadeOnDelete();
            $table->foreignId('promotion_banner_type_id')->constrained('lunar_promotion_banner_types');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lunar_promotion_banners');
    }
};

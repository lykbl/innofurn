<?php

declare(strict_types=1);

use App\Models\PromotionBanner\PromotionBannerType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('lunar_promotion_banner_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('handle');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        PromotionBannerType::create(['name' => 'Card', 'handle' => 'card'])->save();
        PromotionBannerType::create(['name' => 'Panel', 'handle' => 'panel'])->save();
        PromotionBannerType::create(['name' => 'Carousel Item', 'handle' => 'carousel_item'])->save();
    }

    public function down(): void
    {
        Schema::dropIfExists('lunar_promotion_banner_types');
    }
};

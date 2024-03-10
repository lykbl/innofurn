<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('lunar_media_promotion_banner', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('promotion_banner_id')->constrained('lunar_promotion_banners')->cascadeOnDelete();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->boolean('primary')->default(0);
            $table->boolean('banner')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lunar_media_promotion_banner');
    }
};

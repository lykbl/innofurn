<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateBrandDiscountTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'brand_discount', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained($this->prefix.'brands')->cascadeOnDelete();
            $table->foreignId('discount_id')->constrained($this->prefix.'discounts')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'brand_discount');
    }
}

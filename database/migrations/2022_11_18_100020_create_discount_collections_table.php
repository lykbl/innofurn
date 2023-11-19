<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateDiscountCollectionsTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'collection_discount', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('discount_id')->constrained($this->prefix.'discounts')->cascadeOnDelete();
            $table->foreignId('collection_id')->constrained($this->prefix.'collections')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'collection_discount');
    }
}

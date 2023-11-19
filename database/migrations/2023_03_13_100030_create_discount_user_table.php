<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateDiscountUserTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'discount_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('discount_id')->constrained($this->prefix.'discounts')->cascadeOnDelete();
            $table->userForeignKey();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'discount_user');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateProductOptionValuesTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'product_option_values', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('product_option_id')->constrained($this->prefix.'product_options');
            $table->json('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'product_option_values');
    }
}

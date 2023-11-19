<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateProductOptionValueProductVariantTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'product_option_value_product_variant', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('value_id')->constrained($this->prefix.'product_option_values');
            $table->foreignId('variant_id')->constrained($this->prefix.'product_variants');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'product_option_value_product_variant');
    }
}

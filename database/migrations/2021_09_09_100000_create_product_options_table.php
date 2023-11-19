<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateProductOptionsTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'product_options', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->json('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'product_options');
    }
}

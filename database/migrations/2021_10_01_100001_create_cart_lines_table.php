<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateCartLinesTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'cart_lines', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('cart_id')->constrained($this->prefix.'carts');
            $table->morphs('purchasable');
            $table->smallInteger('quantity')->unsigned();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'cart_lines');
    }
}

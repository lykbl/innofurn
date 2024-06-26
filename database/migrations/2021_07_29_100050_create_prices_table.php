<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->prefix.'prices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_group_id')->nullable()->constrained($this->prefix.'customer_groups');
            $table->foreignId('currency_id')->nullable()->constrained($this->prefix.'currencies');
            $table->morphs('priceable');
            $table->integer('price')->unsigned()->index();
            $table->integer('compare_price')->unsigned()->nullable();
            $table->integer('tier')->default(1)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'prices');
    }
}

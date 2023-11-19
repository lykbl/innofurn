<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class UpdatePricesOnPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table($this->prefix.'prices', function (Blueprint $table): void {
            $table->unsignedBigInteger('price')->change();
            $table->unsignedBigInteger('compare_price')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table($this->prefix.'prices', function (Blueprint $table): void {
            $table->unsignedInteger('price')->change();
            $table->unsignedInteger('compare_price')->change();
        });
    }
}

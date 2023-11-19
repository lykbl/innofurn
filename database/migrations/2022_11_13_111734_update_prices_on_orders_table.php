<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class UpdatePricesOnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table($this->prefix.'orders', function (Blueprint $table): void {
            $table->unsignedBigInteger('sub_total')->change();
            $table->unsignedBigInteger('discount_total')->change();
            $table->unsignedBigInteger('shipping_total')->change();
            $table->unsignedBigInteger('tax_total')->change();
            $table->unsignedBigInteger('total')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table($this->prefix.'orders', function (Blueprint $table): void {
            $table->unsignedInteger('sub_total')->change();
            $table->unsignedInteger('discount_total')->change();
            $table->unsignedInteger('shipping_total')->change();
            $table->unsignedInteger('tax_total')->change();
            $table->unsignedInteger('total')->change();
        });
    }
}

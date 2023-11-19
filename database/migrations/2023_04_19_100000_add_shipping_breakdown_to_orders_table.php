<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddShippingBreakdownToOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'orders', function (Blueprint $table): void {
            $table->json('shipping_breakdown')->nullable()->after('discount_total');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'orders', function ($table): void {
            $table->dropColumn('shipping_breakdown');
        });
    }
}

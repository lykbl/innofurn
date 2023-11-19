<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddDiscountBreakdownToOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'orders', function (Blueprint $table): void {
            $table->json('discount_breakdown')->nullable()->after('sub_total');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'orders', function ($table): void {
            $table->dropColumn('discount_breakdown');
        });
    }
}

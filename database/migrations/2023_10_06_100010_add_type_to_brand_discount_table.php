<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddTypeToBrandDiscountTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'brand_discount', function (Blueprint $table): void {
            $table->string('type', 20)->after('discount_id')->default('limitation');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'brand_discount', function ($table): void {
            $table->dropColumn('type');
        });
    }
}

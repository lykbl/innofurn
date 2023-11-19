<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddMaxUsesPerUserToDiscountsTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'discounts', function (Blueprint $table): void {
            $table->mediumInteger('max_uses_per_user')->unsigned()->nullable()->after('max_uses');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'discounts', function ($table): void {
            $table->dropColumn('max_uses_per_user');
        });
    }
}

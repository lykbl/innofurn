<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddLabelToProductOptionsTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'product_options', function (Blueprint $table): void {
            $table->json('label')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'product_options', function ($table): void {
            $table->dropColumn('label');
        });
    }
}

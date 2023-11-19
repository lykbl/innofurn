<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddAttributesToBrandsTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'brands', function (Blueprint $table): void {
            $table->json('attribute_data')->after('name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'brands', function ($table): void {
            $table->dropColumn('attribute_data');
        });
    }
}

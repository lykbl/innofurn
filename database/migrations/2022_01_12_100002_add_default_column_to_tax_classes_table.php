<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddDefaultColumnToTaxClassesTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'tax_classes', function (Blueprint $table): void {
            $table->boolean('default')->after('name')->index()->default(false);
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'tax_classes', function (Blueprint $table): void {
            $table->dropColumn('default');
        });
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddColumnsToAttributesTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'attributes', function (Blueprint $table): void {
            $table->boolean('searchable')->after('system')->default(true)->index();
            $table->boolean('filterable')->after('system')->default(false)->index();
            $table->string('validation_rules')->after('system')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'attributes', function (Blueprint $table): void {
            $table->dropColumn(['searchable', 'filterable', 'validation_rules']);
        });
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class SetSectionToNullableOnAttributes extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'attributes', function (Blueprint $table): void {
            $table->string('section')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'attributes', function ($table): void {
            $table->string('section')->nullable(false)->change();
        });
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class UpdateQuantityOnCartLinesTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'cart_lines', function (Blueprint $table): void {
            $table->unsignedInteger('quantity')->change();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'cart_lines', function ($table): void {
            $table->smallInteger('quantity')->unsigned()->change();
        });
    }
}

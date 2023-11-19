<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddHandlePositionToProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table($this->prefix.'product_options', function (Blueprint $table): void {
            // @note Made nullable for now to avoid breaking changes.
            $table->string('handle')->after('name')->unique()->nullable();
            $table->integer('position')->after('name')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table($this->prefix.'product_options', function (Blueprint $table): void {
            $table->dropColumn(['handle', 'position']);
        });
    }
}

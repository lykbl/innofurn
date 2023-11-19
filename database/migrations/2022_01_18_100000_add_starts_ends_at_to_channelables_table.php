<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddStartsEndsAtToChannelablesTable extends Migration
{
    public function up(): void
    {
        /*
         * SQLite will only allow one per transaction when modifying columns.
         */
        Schema::table($this->prefix.'channelables', function (Blueprint $table): void {
            $table->renameColumn('published_at', 'starts_at');
        });

        Schema::table($this->prefix.'channelables', function (Blueprint $table): void {
            $table->dateTime('ends_at')->after('starts_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'channelables', function ($table): void {
            $table->renameColumn('starts_at', 'published_at');
        });

        Schema::table($this->prefix.'channelables', function ($table): void {
            $table->dropColumn('ends_at');
        });
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;
use Lunar\Facades\DB;

class AddFieldsToTransactionsTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'transactions', function (Blueprint $table): void {
            $table->foreignId('parent_transaction_id')->after('id')
                ->nullable()
                ->constrained($this->prefix.'transactions');
            $table->dateTime('captured_at')->nullable()->index();
            $table->enum('type', ['refund', 'intent', 'capture'])->after('success')->index()->default('capture');
        });

        Schema::table($this->prefix.'transactions', function (Blueprint $table): void {
            $table->dropColumn('refund');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'transactions', function ($table): void {
            if ('sqlite' !== DB::getDriverName()) {
                $table->dropForeign(['parent_transaction_id']);
            }
            $table->dropColumn(['parent_transaction_id', 'type']);
        });

        Schema::table($this->prefix.'transactions', function ($table): void {
            $table->boolean('refund')->default(false)->index();
        });
    }
}

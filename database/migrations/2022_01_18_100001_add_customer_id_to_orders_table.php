<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;
use Lunar\Facades\DB;

class AddCustomerIdToOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'orders', function (Blueprint $table): void {
            $table->foreignId('customer_id')->after('id')
                ->nullable()
                ->constrained($this->prefix.'customers');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'orders', function ($table): void {
            if ('sqlite' !== DB::getDriverName()) {
                $table->dropForeign(['customer_id']);
            }
            $table->dropColumn('customer_id');
        });
    }
}

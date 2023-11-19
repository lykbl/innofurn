<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddCustomerIdToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table($this->prefix.'carts', function (Blueprint $table): void {
            $table->foreignId('customer_id')->after('user_id')
                ->nullable()
                ->constrained($this->prefix.'customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table($this->prefix.'carts', function (Blueprint $table): void {
            if ('sqlite' !== DB::getDriverName()) {
                $table->dropForeign(['customer_id']);
            }

            $table->dropColumn('customer_id');
        });
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddAccountRefToCustomersTable extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'customers', function (Blueprint $table): void {
            $table->string('account_ref')->nullable()->index()->after('vat_no');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'customers', function (Blueprint $table): void {
            $table->dropColumn('account_ref');
        });
    }
}

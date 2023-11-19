<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateCustomerCustomerGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->prefix.'customer_customer_group', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained($this->prefix.'customers');
            $table->foreignId('customer_group_id')->constrained($this->prefix.'customer_groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'customer_customer_group');
    }
}

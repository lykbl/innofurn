<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->prefix.'currencies', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('exchange_rate', 10, 4);
            $table->string('format');
            $table->string('decimal_point');
            $table->string('thousand_point');
            $table->integer('decimal_places')->default(2)->index();
            $table->boolean('enabled')->default(0)->index();
            $table->boolean('default')->default(0)->index();
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
        Schema::dropIfExists($this->prefix.'currencies');
    }
}

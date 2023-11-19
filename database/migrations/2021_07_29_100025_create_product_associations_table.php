<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateProductAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->prefix.'product_associations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_parent_id')->constrained($this->prefix.'products');
            $table->foreignId('product_target_id')->constrained($this->prefix.'products');
            $table->string('type')->index();
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
        Schema::dropIfExists($this->prefix.'product_associations');
    }
}

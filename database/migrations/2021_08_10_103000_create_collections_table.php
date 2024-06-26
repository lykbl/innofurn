<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->prefix.'collections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('collection_group_id')->constrained($this->prefix.'collection_groups');
            $table->nestedSet();
            $table->string('type')->default('static')->index();
            $table->json('attribute_data');
            $table->string('sort')->default('custom')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'collections');
    }
}

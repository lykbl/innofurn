<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateSavedSearchesTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'saved_searches', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('staff_id')->constrained(
                $this->prefix.'staff'
            )->cascadeOnDelete();
            $table->string('name');
            $table->string('component')->index();
            $table->string('term')->nullable();
            $table->json('filters')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'saved_searches');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateTaggablesTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'taggables', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('tag_id')->constrained($this->prefix.'tags');
            $table->morphs('taggable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'taggables');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateUrlsTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'urls', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('language_id')->constrained($this->prefix.'languages');
            $table->morphs('element');
            $table->string('slug')->index();
            $table->boolean('default')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'urls');
    }
}

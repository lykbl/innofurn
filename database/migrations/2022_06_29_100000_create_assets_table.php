<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateAssetsTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'assets', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'assets');
    }
}

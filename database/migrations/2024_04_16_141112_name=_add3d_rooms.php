<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $schema): void {
            $schema->id();
            $schema->foreignId('product_variant_id')->constrained('lunar_product_variants');
            $schema->string('title');
            $schema->point('camera_position');
            $schema->point('look_at');
            $schema->string('glb_location');
            $schema->timestamps();
            $schema->softDeletes();
            $schema->boolean('active');
        });

        Schema::create('room_meshes', function (Blueprint $schema): void {
            $schema->id();
            $schema->point('position');
            $schema->point('rotation');
            $schema->string('material');
            $schema->geometry('geometry');
            $schema->foreignId('room_id')->constrained('rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_meshes');
    }
};

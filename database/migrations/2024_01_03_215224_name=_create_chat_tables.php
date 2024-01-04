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
        Schema::create('chat_rooms', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->unsignedBigInteger('customer_id')->nullable();
            $blueprint->foreign('customer_id')->references('id')->on('lunar_customers');
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignId('chat_room_id')->references('id')->on('chat_rooms')->constrained();
            $blueprint->unsignedBigInteger('customer_id')->nullable();
            $blueprint->foreign('customer_id')->references('id')->on('lunar_customers');
            $blueprint->addColumn('text', 'body');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_rooms');
    }
};

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
            $blueprint->foreignId('customer_id')->constrained('lunar_customers');
            $blueprint->timestamp('created_at')->useCurrent();
            $blueprint->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $blueprint->softDeletes();
        });

        Schema::create('chat_messages', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignId('chat_room_id')->constrained('chat_rooms');
            $blueprint->foreignId('customer_id')->nullable()->constrained('lunar_customers');
            $blueprint->foreignId('staff_id')->nullable()->constrained('lunar_staff');
            $blueprint->addColumn('text', 'body');
            $blueprint->timestamp('created_at', 3)->useCurrent();
            $blueprint->timestamp('updated_at', 3)->nullable()->useCurrentOnUpdate();
            $blueprint->timestamp('edited_at', 3)->nullable();
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

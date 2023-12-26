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
        Schema::create('oauth_users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['google', 'github']);
            $table->string('oauth_id');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_users');
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }
};

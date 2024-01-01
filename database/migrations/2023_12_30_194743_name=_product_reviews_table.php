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
        Schema::create('reviews', function (Blueprint $query): void {
            $query->id();
            $query->unsignedBigInteger('product_variant_id');
            $query->unsignedBigInteger('user_id');
            $query->string('title');
            $query->text('body');
            $query->unsignedTinyInteger('rating');
            $query->timestamps();
            $query->timestamp('approved_at')->nullable();
            $query->timestamp('archived_at')->nullable();
            $query->softDeletes();

            $query->foreign('product_variant_id')->references('id')->on('lunar_product_variants');
            $query->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

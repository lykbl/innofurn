<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateStaffPermissionsTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'staff_permissions', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('staff_id')->constrained(
                $this->prefix.'staff'
            )->cascadeOnDelete();
            $table->string('handle')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'staff_permissions');
    }
}

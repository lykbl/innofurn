<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class RemoveStaffPermissionsTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists($this->prefix.'staff_permissions');
    }

    public function down(): void
    {
    }
}

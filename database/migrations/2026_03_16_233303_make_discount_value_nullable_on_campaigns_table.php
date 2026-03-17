<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE campaigns MODIFY discount_value INT UNSIGNED NULL"
        );
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE campaigns MODIFY discount_value INT UNSIGNED NOT NULL DEFAULT 0"
        );
    }
};

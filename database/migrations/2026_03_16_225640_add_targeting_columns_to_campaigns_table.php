<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('target_type')->default('any')->after('valid_until');
            $table->json('target_items')->nullable()->after('target_type');
            $table->string('applicable_to')->default('any')->after('target_items');
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE campaigns MODIFY discount_type ENUM('percentage', 'fixed', 'duration') DEFAULT 'percentage'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['target_type', 'target_items', 'applicable_to']);
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE campaigns MODIFY discount_type ENUM('percentage', 'fixed') DEFAULT 'percentage'");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->nullOnDelete()->after('channel');
            $table->unsignedBigInteger('discount_amount')->default(0)->after('campaign_id');
            $table->unsignedInteger('duration_bonus')->default(0)->after('discount_amount');
            $table->boolean('is_renewal')->nullable()->after('duration_bonus');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
            $table->dropColumn(['campaign_id', 'discount_amount', 'duration_bonus', 'is_renewal']);
        });
    }
};

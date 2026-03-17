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
        Schema::table('agenda_event_registrations', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable()->after('id')->constrained('transactions')->nullOnDelete();
            $table->dropColumn('event_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_event_registrations', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');
            $table->unsignedInteger('event_price')->nullable();
        });
    }
};

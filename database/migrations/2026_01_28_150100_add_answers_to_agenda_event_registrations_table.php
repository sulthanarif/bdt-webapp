<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('agenda_event_registrations', 'answers')) {
            Schema::table('agenda_event_registrations', function (Blueprint $table) {
                $table->json('answers')->nullable()->after('event_price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('agenda_event_registrations', 'answers')) {
            Schema::table('agenda_event_registrations', function (Blueprint $table) {
                $table->dropColumn('answers');
            });
        }
    }
};

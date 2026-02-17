<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('agenda_events', 'registration_fields')) {
            Schema::table('agenda_events', function (Blueprint $table) {
                $table->json('registration_fields')->nullable()->after('use_internal_registration');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('agenda_events', 'registration_fields')) {
            Schema::table('agenda_events', function (Blueprint $table) {
                $table->dropColumn('registration_fields');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('agenda_events', 'use_internal_registration')) {
            Schema::table('agenda_events', function (Blueprint $table) {
                $table->boolean('use_internal_registration')->default(false)->after('cta_style');
            });
        }

        if (! Schema::hasColumn('agenda_events', 'image_path')) {
            Schema::table('agenda_events', function (Blueprint $table) {
                $table->string('image_path')->nullable()->after('status_label');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('agenda_events', 'use_internal_registration')) {
            Schema::table('agenda_events', function (Blueprint $table) {
                $table->dropColumn('use_internal_registration');
            });
        }

        if (Schema::hasColumn('agenda_events', 'image_path')) {
            Schema::table('agenda_events', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }
};

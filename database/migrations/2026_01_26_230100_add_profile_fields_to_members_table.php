<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('nik');
            $table->string('domicile')->nullable()->after('birth_date');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['nik', 'birth_date', 'domicile']);
        });
    }
};

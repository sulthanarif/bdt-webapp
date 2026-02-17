<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_type_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_type_id')
                ->constrained('member_types')
                ->cascadeOnDelete();
            $table->string('label');
            $table->boolean('is_included')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['member_type_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_type_benefits');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_type_id')->nullable()
                ->constrained('member_types')
                ->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('nim')->nullable();
            $table->string('university')->nullable();
            $table->boolean('is_verified_student')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['member_type_id']);
            $table->index(['status']);
            $table->index(['email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

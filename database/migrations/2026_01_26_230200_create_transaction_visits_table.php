<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->cascadeOnDelete();
            $table->foreignId('member_type_id')
                ->nullable()
                ->constrained('member_types')
                ->nullOnDelete();
            $table->date('visit_date')->nullable();
            $table->string('gender', 20)->nullable();
            $table->unsignedInteger('qty')->default(1);
            $table->timestamps();

            $table->index(['member_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_visits');
    }
};

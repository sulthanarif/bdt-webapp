<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('invoice_id')->nullable()->unique();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('amount_total')->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->string('channel')->default('online');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['member_id']);
            $table->index(['status', 'channel']);
            $table->index(['created_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

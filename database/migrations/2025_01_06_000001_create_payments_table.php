<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('method')->default('qris_manual'); // qris_manual, qris_gateway, dst
            $table->unsignedBigInteger('amount');
            $table->enum('status', ['pending', 'waiting_review', 'approved', 'rejected', 'expired'])->default('pending');
            $table->string('gateway_reference')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

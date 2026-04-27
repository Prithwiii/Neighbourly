<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable()->index();
            $table->string('donor_name');
            $table->string('donor_email')->nullable();
            $table->string('donor_phone');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('BDT');
            $table->string('purpose')->nullable();
            $table->text('message')->nullable();
            $table->string('gateway_name')->default('sslcommerz');
            $table->string('gateway_url')->nullable();
            $table->string('gateway_session_key')->nullable();
            $table->string('gateway_validation_id')->nullable();
            $table->string('status')->default('pending');
            $table->text('fail_reason')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_payments');
    }
};
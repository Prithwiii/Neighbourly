<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('phone', 30);
            $table->string('service_category');
            $table->string('location');
            $table->unsignedInteger('experience_years')->default(0);
            $table->text('description');
            $table->enum('availability_status', ['available', 'busy', 'offline'])->default('available');
            $table->string('availability_details')->nullable();
            $table->string('document_path')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected', 'flagged'])->default('pending');
            $table->string('phone_otp', 6)->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('fraud_note')->nullable();
            $table->timestamps();

            $table->index(['verification_status', 'service_category']);
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_providers');
    }
};

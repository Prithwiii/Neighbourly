<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_in_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('work_type', ['elderly_care', 'childcare', 'home_help', 'companionship', 'medical_support', 'other']);
            $table->enum('urgency_level', ['low', 'medium', 'high']);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('location_name')->nullable();
            $table->string('phone_number');
            $table->enum('status', ['open', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('open');
            $table->timestamp('preferred_date')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('urgency_level');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_in_requests');
    }
};

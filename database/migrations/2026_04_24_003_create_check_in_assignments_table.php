<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_in_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_in_request_id')->constrained('check_in_requests')->cascadeOnDelete();
            $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['check_in_request_id', 'volunteer_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_in_assignments');
    }
};

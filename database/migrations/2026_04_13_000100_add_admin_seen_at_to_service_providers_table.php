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
        Schema::table('service_providers', function (Blueprint $table) {
            $table->timestamp('admin_seen_at')->nullable()->after('verified_at');
            $table->index(['verification_status', 'admin_seen_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->dropIndex(['verification_status', 'admin_seen_at']);
            $table->dropColumn('admin_seen_at');
        });
    }
};

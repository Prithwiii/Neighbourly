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
        if (Schema::hasColumn('emergency_alerts', 'admin_contacted_via')) {
            Schema::table('emergency_alerts', function (Blueprint $table) {
                $table->dropColumn('admin_contacted_via');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('emergency_alerts', 'admin_contacted_via')) {
            Schema::table('emergency_alerts', function (Blueprint $table) {
                $table->string('admin_contacted_via', 50)->nullable()->after('status');
            });
        }
    }
};

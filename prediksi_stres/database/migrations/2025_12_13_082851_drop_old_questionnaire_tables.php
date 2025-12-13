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
        Schema::dropIfExists('stress_factors');
        Schema::dropIfExists('questionnaires');
        
        // Remove old migrations from migrations table
        \DB::table('migrations')
            ->whereIn('migration', [
                '2024_01_01_000004_create_questionnaires_table',
                '2024_01_01_000005_create_stress_factors_table',
                '2025_12_10_015246_update_questionnaires_table',
                '2025_12_13_080159_add_health_condition_2_to_questionnaires_table'
            ])
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We can't recreate the old tables here as we don't have their full structure
        // This is a one-way migration to clean up old tables
    }
};

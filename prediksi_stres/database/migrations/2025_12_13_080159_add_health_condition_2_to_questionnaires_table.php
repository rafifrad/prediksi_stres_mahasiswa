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
        Schema::table('questionnaires', function (Blueprint $table) {
            if (!Schema::hasColumn('questionnaires', 'academic_load')) {
                $table->integer('academic_load')->after('prediction_id')->nullable()->comment('Beban akademik (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'sleep_hours')) {
                $table->integer('sleep_hours')->after('academic_load')->nullable()->comment('Jam tidur per hari');
            }
            if (!Schema::hasColumn('questionnaires', 'social_support')) {
                $table->integer('social_support')->after('sleep_hours')->nullable()->comment('Dukungan sosial (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'financial_stress')) {
                $table->integer('financial_stress')->after('social_support')->nullable()->comment('Stres finansial (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'time_management')) {
                $table->integer('time_management')->after('financial_stress')->nullable()->comment('Manajemen waktu (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'health_condition')) {
                $table->integer('health_condition')->after('time_management')->nullable()->comment('Kondisi kesehatan (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'family_issues')) {
                $table->integer('family_issues')->after('health_condition')->nullable()->comment('Masalah keluarga (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'relationship_status')) {
                $table->integer('relationship_status')->after('family_issues')->nullable()->comment('Status hubungan (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'study_environment')) {
                $table->integer('study_environment')->after('relationship_status')->nullable()->comment('Lingkungan belajar (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'future_anxiety')) {
                $table->integer('future_anxiety')->after('study_environment')->nullable()->comment('Kecemasan masa depan (1-5)');
            }
            if (!Schema::hasColumn('questionnaires', 'health_condition_2')) {
                $table->integer('health_condition_2')->after('health_condition')->nullable()->comment('Rasa tidak sanggup (1-5)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            if (Schema::hasColumn('questionnaires', 'health_condition_2')) {
                $table->dropColumn('health_condition_2');
            }
        });
    }
};

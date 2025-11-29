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
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_id')->constrained()->onDelete('cascade');
            $table->integer('academic_load')->comment('Beban akademik (1-5)');
            $table->integer('sleep_hours')->comment('Jam tidur per hari');
            $table->integer('social_support')->comment('Dukungan sosial (1-5)');
            $table->integer('financial_stress')->comment('Stres finansial (1-5)');
            $table->integer('time_management')->comment('Manajemen waktu (1-5)');
            $table->integer('health_condition')->comment('Kondisi kesehatan (1-5)');
            $table->integer('family_issues')->comment('Masalah keluarga (1-5)');
            $table->integer('relationship_status')->comment('Status hubungan (1-5)');
            $table->integer('study_environment')->comment('Lingkungan belajar (1-5)');
            $table->integer('future_anxiety')->comment('Kecemasan masa depan (1-5)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaires');
    }
};


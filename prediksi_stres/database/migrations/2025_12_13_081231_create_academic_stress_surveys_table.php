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
        Schema::create('academic_stress_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_id')->constrained()->onDelete('cascade');
            
            // Stress factors
            $table->float('Tekanan_Akademik', 8, 2)->nullable()->comment('Tekanan dari beban akademik');
            $table->float('Kesulitan_Akumulasi', 8, 2)->nullable()->comment('Akumulasi kesulitan');
            $table->float('Stres_Tugas_Deadline', 8, 2)->nullable()->comment('Stres dari tugas & deadline');
            $table->float('Tekanan_Eksternal', 8, 2)->nullable()->comment('Tekanan eksternal');
            $table->float('Kurang_Kendali', 8, 2)->nullable()->comment('Kurang kendali');
            $table->float('Rasa_Tidak_Sanggup', 8, 2)->nullable()->comment('Rasa tidak sanggup');
            $table->float('Stres_Pribadi', 8, 2)->nullable()->comment('Stres pribadi');
            $table->float('Marah_Eksternal_Studi', 8, 2)->nullable()->comment('Marah eksternal studi');
            $table->float('Stres_Perubahan_Akademik', 8, 2)->nullable()->comment('Stres dari perubahan akademik');
            $table->float('Tekanan_IPK', 8, 2)->nullable()->comment('Tekanan IPK');
            $table->float('Cemas_Karir', 8, 2)->nullable()->comment('Kecemasan karir');
            $table->float('Kebiasaan_Buruk', 8, 2)->nullable()->comment('Kebiasaan buruk');
            $table->float('Proses_Sesuai_Harapan', 8, 2)->nullable()->comment('Proses belajar sesuai harapan');
            
            // Aggregated scores
            $table->float('Academic_Stress_Score', 8, 2)->nullable()->comment('Skor stres akademik');
            $table->float('Academic_Confidence_Score', 8, 2)->nullable()->comment('Skor kepercayaan diri akademik');
            
            $table->timestamps();
            
            // Add indexes for frequently queried columns
            $table->index('prediction_id');
            $table->index('Academic_Stress_Score');
            $table->index('Academic_Confidence_Score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_stress_surveys');
    }
};

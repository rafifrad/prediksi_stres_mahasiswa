<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicStressSurvey extends Model
{
    protected $table = 'academic_stress_surveys';
    
    protected $fillable = [
        'prediction_id',
        'Tekanan_Akademik',
        'Kesulitan_Akumulasi',
        'Stres_Tugas_Deadline',
        'Tekanan_Eksternal',
        'Kurang_Kendali',
        'Rasa_Tidak_Sanggup',
        'Stres_Pribadi',
        'Marah_Eksternal_Studi',
        'Stres_Perubahan_Akademik',
        'Tekanan_IPK',
        'Cemas_Karir',
        'Kebiasaan_Buruk',
        'Proses_Sesuai_Harapan',
        'Academic_Stress_Score',
        'Academic_Confidence_Score',
    ];

    protected $casts = [
        'Tekanan_Akademik' => 'float',
        'Kesulitan_Akumulasi' => 'float',
        'Stres_Tugas_Deadline' => 'float',
        'Tekanan_Eksternal' => 'float',
        'Kurang_Kendali' => 'float',
        'Rasa_Tidak_Sanggup' => 'float',
        'Stres_Pribadi' => 'float',
        'Marah_Eksternal_Studi' => 'float',
        'Stres_Perubahan_Akademik' => 'float',
        'Tekanan_IPK' => 'float',
        'Cemas_Karir' => 'float',
        'Kebiasaan_Buruk' => 'float',
        'Proses_Sesuai_Harapan' => 'float',
        'Academic_Stress_Score' => 'float',
        'Academic_Confidence_Score' => 'float',
    ];

    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }
}

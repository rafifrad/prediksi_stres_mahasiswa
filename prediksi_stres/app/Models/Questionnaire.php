<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'prediction_id',
        'academic_load',
        'sleep_hours',
        'social_support',
        'financial_stress',
        'time_management',
        'health_condition',
        'family_issues',
        'relationship_status',
        'study_environment',
        'future_anxiety',
    ];

    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stress_level',
        'confidence_score',
    ];

    protected $casts = [
        'confidence_score' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function questionnaire(): HasOne
    {
        return $this->hasOne(Questionnaire::class);
    }

    public function stressFactors(): HasMany
    {
        return $this->hasMany(StressFactor::class)->orderBy('rank');
    }
}


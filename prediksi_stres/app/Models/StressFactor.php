<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StressFactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'prediction_id',
        'factor_name',
        'importance_score',
        'rank',
    ];

    protected $casts = [
        'importance_score' => 'decimal:2',
    ];

    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }
}


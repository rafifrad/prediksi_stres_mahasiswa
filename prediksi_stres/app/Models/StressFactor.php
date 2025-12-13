<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StressFactor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prediction_id',
        'factor_name',
        'importance_score',
        'rank',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'importance_score' => 'decimal:2',
        'rank' => 'integer',
    ];

    /**
     * Get the prediction that owns the stress factor.
     */
    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }
}

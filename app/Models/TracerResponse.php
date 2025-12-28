<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TracerResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracer_study_id',
        'user_id',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function tracerStudy(): BelongsTo
    {
        return $this->belongsTo(TracerStudy::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(TracerAnswer::class, 'response_id');
    }
}

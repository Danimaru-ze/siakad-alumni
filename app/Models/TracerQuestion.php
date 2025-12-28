<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TracerQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracer_study_id',
        'pertanyaan',
        'tipe',
        'pilihan',
        'wajib_diisi',
        'urutan',
    ];

    protected $casts = [
        'pilihan' => 'array',
        'wajib_diisi' => 'boolean',
    ];

    public function tracerStudy(): BelongsTo
    {
        return $this->belongsTo(TracerStudy::class, 'tracer_study_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(TracerAnswer::class, 'question_id');
    }
}
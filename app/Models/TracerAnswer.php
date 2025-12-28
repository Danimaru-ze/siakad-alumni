<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracerAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'question_id',
        'jawaban',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(TracerResponse::class, 'response_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(TracerQuestion::class, 'question_id');
    }
}

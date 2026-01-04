<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvProfile extends Model
{
    protected $fillable = [
        'user_id',
        'professional_summary',
        'linkedin_url',
        'portfolio_url',
        'phone_number',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
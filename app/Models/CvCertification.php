<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvCertification extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'issuer',
        'issue_date',
        'expiration_date',
        'credential_id',
        'credential_url',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiration_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
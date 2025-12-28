<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pekerjaan',
        'perusahaan',
        'jabatan',
        'masa_tunggu_kerja',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
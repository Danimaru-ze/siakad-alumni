<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TracerStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'target_angkatan',
        'target_prodi_ids',
        'tanggal_mulai',
        'tanggal_selesai',
        'created_by',
        'fakultas_id',
    ];

    protected $casts = [
        'target_prodi_ids' => 'array',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(TracerQuestion::class)->orderBy('urutan');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(TracerResponse::class);
    }
}
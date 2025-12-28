<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        'email',
        'website',
        'alamat',
        'telepon',
    ];

    public function hrdProfiles(): HasMany
    {
        return $this->hasMany(HrdProfile::class);
    }

    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }
}
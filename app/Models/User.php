<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'fakultas_id',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =============================
    // ACCESSORS
    // =============================
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // =============================
    // RELATIONS
    // =============================

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function dosen(): HasOne
    {
        return $this->hasOne(Dosen::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function alumniProfile(): HasOne
    {
        return $this->hasOne(AlumniProfile::class);
    }

    public function hrdProfile(): HasOne
    {
        return $this->hasOne(HrdProfile::class);
    }

    public function createdJobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'hrd_user_id');
    }

    public function approvedJobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'approved_by');
    }

    // =============================
    // ROLE HELPERS
    // =============================

    /**
     * Check if user is superadmin (full access)
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is admin (faculty-level or superadmin)
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'admin_fakultas']);
    }

    /**
     * Check if user can access a specific fakultas
     */
    public function canAccessFakultas(int $fakultasId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->fakultas_id === $fakultasId;
    }

    /**
     * Get fakultas IDs this user can access
     */
    public function getAccessibleFakultasIds(): array
    {
        if ($this->isSuperAdmin()) {
            return Fakultas::pluck('id')->toArray();
        }

        return $this->fakultas_id ? [$this->fakultas_id] : [];
    }
}

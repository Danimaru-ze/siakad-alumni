<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobVacancy extends Model
{
    use HasFactory;

    protected $table = 'job_vacancies';

    protected $fillable = [
        'company_id',
        'hrd_user_id',
        'judul',
        'deskripsi',
        'persyaratan',
        'lokasi',
        'tipe_pekerjaan',
        'kisaran_gaji_min',
        'kisaran_gaji_max',
        'deadline_lamaran',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string', // Using string instead of Enum class to match migration
            'approved_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function hrd(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hrd_user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // SCOPES
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return match ($user->role) {
            'superadmin' => $query, // No filter for superadmin
            'admin_fakultas' => $query->whereIn('status', ['PENDING', 'PUBLISHED', 'REJECTED']),
            'dosen' => $query->where('status', 'PUBLISHED'),
            'hrd' => $query->where('hrd_user_id', $user->id),
            'alumni' => $query->where('status', 'PUBLISHED'),
            default => $query->whereRaw('1 = 0'), // Empty result for others like mahasiswa
        };
    }
}
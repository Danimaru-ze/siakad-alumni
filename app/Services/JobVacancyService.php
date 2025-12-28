<?php

namespace App\Services;

use App\Exceptions\HrdProfileNotCompleteException;
use App\Models\JobVacancy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JobVacancyService
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Create a new job vacancy.
     * @throws HrdProfileNotCompleteException
     */
    public function create(array $data, User $hrdUser): JobVacancy
    {
        if (!$hrdUser->hrdProfile || !$hrdUser->hrdProfile->company) {
            throw new HrdProfileNotCompleteException();
        }

        $jobVacancy = JobVacancy::create([
            'company_id' => $hrdUser->hrdProfile->company_id,
            'hrd_user_id' => $hrdUser->id,
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'persyaratan' => $data['persyaratan'],
            'lokasi' => $data['lokasi'],
            'tipe_pekerjaan' => $data['tipe_pekerjaan'],
            'kisaran_gaji_min' => $data['kisaran_gaji_min'] ?? null,
            'kisaran_gaji_max' => $data['kisaran_gaji_max'] ?? null,
            'deadline_lamaran' => $data['deadline_lamaran'] ?? null,
            'status' => 'DRAFT',
        ]);

        $this->activityLogService->logCreate($jobVacancy);

        return $jobVacancy;
    }

    /**
     * Update an existing job vacancy.
     */
    public function update(JobVacancy $jobVacancy, array $data): JobVacancy
    {
        $oldValues = $jobVacancy->getOriginal();
        
        $jobVacancy->update($data);

        $this->activityLogService->logUpdate($jobVacancy, $oldValues);

        return $jobVacancy;
    }

    /**
     * Delete a job vacancy.
     */
    public function delete(JobVacancy $jobVacancy): void
    {
        DB::transaction(function () use ($jobVacancy) {
            $this->activityLogService->logDelete($jobVacancy);
            $jobVacancy->delete();
        });
    }

    /**
     * Submit a job vacancy for approval.
     */
    public function submitForApproval(JobVacancy $jobVacancy): JobVacancy
    {
        $jobVacancy->status = 'PENDING';
        $jobVacancy->save();

        $this->activityLogService->log('submit_for_approval', $jobVacancy);

        return $jobVacancy;
    }

    /**
     * Approve a job vacancy.
     */
    public function approve(JobVacancy $jobVacancy, User $approver): JobVacancy
    {
        $jobVacancy->status = 'PUBLISHED';
        $jobVacancy->approved_by = $approver->id;
        $jobVacancy->approved_at = Carbon::now();
        $jobVacancy->save();

        $this->activityLogService->log('approve', $jobVacancy, ['approver_id' => $approver->id]);

        return $jobVacancy;
    }

    /**
     * Reject a job vacancy.
     */
    public function reject(JobVacancy $jobVacancy, User $rejector, ?string $reason = null): JobVacancy
    {
        $jobVacancy->status = 'REJECTED';
        $jobVacancy->save();

        $this->activityLogService->log('reject', $jobVacancy, [
            'rejector_id' => $rejector->id,
            'reason' => $reason
        ]);

        return $jobVacancy;
    }
    
    /**
     * Flag a job vacancy for review.
     */
    public function flag(JobVacancy $jobVacancy, User $flagger, string $reason): void
    {
        // For now, we only log this action. No status change.
        $this->activityLogService->log('flag', $jobVacancy, [
            'flagger_id' => $flagger->id,
            'reason' => $reason,
        ]);
        
        // Notification logic would be triggered here in a real scenario
    }
}

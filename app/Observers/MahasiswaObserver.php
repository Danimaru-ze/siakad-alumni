<?php

namespace App\Observers;

use App\Models\Mahasiswa;
use App\Models\User;

class MahasiswaObserver
{
    /**
     * Handle the Mahasiswa "updated" event.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return void
     */
    public function updated(Mahasiswa $mahasiswa): void
    {
        // Check if the 'status' field was changed, otherwise do nothing.
        if (! $mahasiswa->isDirty('status')) {
            return;
        }

        $user = $mahasiswa->user;
        if (! $user) {
            return;
        }

        // Case 1: Student's status changes TO 'lulus'.
        // Update user role to 'alumni'.
        if ($mahasiswa->status === 'lulus') {
            if ($user->role !== 'alumni') {
                $user->role = 'alumni';
                $user->save();
            }
        }
        // Case 2: A graduated student's status is changed FROM 'lulus' back to something else (e.g., 'aktif').
        // Revert user role to 'mahasiswa'.
        elseif ($mahasiswa->getOriginal('status') === 'lulus') {
            if ($user->role === 'alumni') {
                $user->role = 'mahasiswa';
                $user->save();
            }
        }
    }
}

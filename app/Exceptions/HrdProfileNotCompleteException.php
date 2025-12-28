<?php

namespace App\Exceptions;

use Exception;

class HrdProfileNotCompleteException extends Exception
{
    protected $message = 'Profil HRD atau perusahaan belum lengkap. Silakan lengkapi profil Anda terlebih dahulu.';
}

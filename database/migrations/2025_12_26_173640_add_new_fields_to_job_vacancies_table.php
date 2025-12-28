<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->unsignedInteger('kisaran_gaji_min')->nullable()->after('tipe_pekerjaan');
            $table->unsignedInteger('kisaran_gaji_max')->nullable()->after('kisaran_gaji_min');
            $table->date('deadline_lamaran')->nullable()->after('kisaran_gaji_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropColumn(['kisaran_gaji_min', 'kisaran_gaji_max', 'deadline_lamaran']);
        });
    }
};
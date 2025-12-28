<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('target_angkatan');
            $table->json('target_prodi_ids')->nullable();
            $table->timestamp('tanggal_mulai');
            $table->timestamp('tanggal_selesai');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};
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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('hrd_user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('lokasi')->nullable();
            $table->string('tipe_pekerjaan')->nullable();
            $table->enum('status', ['DRAFT', 'PENDING', 'PUBLISHED', 'REJECTED'])->default('DRAFT');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
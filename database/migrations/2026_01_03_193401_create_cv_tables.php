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
        // Tabel Profil Tambahan untuk CV
        Schema::create('cv_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('professional_summary')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('phone_number')->nullable(); // Nomor HP khusus CV jika beda
            $table->string('address')->nullable(); // Alamat singkat
            $table->timestamps();
        });

        // Tabel Pengalaman Kerja
        Schema::create('cv_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company');
            $table->string('role');
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Null = Present
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable(); // Bullet points
            $table->timestamps();
        });

        // Tabel Pendidikan Tambahan (Optional, selain data akademik kampus)
        Schema::create('cv_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('institution');
            $table->string('degree');
            $table->string('field_of_study')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable(); // Achievements/Activities
            $table->timestamps();
        });

        // Tabel Skill
        Schema::create('cv_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category')->default('Hard Skills'); // Hard Skills, Soft Skills, Languages, Tools
            $table->string('name');
            $table->string('level')->nullable(); // Beginner, Intermediate, Expert
            $table->timestamps();
        });

        // Tabel Sertifikasi
        Schema::create('cv_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('issuer');
            $table->date('issue_date');
            $table->date('expiration_date')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->timestamps();
        });

        // Tabel Proyek Portofolio
        Schema::create('cv_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('role')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_projects');
        Schema::dropIfExists('cv_certifications');
        Schema::dropIfExists('cv_skills');
        Schema::dropIfExists('cv_educations');
        Schema::dropIfExists('cv_experiences');
        Schema::dropIfExists('cv_profiles');
    }
};
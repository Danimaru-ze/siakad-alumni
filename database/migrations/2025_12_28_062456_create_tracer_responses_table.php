<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracer_study_id')->constrained('tracer_studies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The user ID of the alumnus
            $table->enum('status', ['BELUM_MENGISI', 'SUDAH_MENGISI'])->default('BELUM_MENGISI');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['tracer_study_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_responses');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracer_study_id')->constrained('tracer_studies')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->string('tipe'); // e.g., 'short_text', 'long_text', 'radio', 'checkbox', 'dropdown'
            $table->json('pilihan')->nullable(); // For multiple choice options
            $table->boolean('wajib_diisi')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_questions');
    }
};
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
        Schema::table('prodi', function (Blueprint $table) {
            // Relasi ke tabel dosen, nullable karena prodi baru mungkin belum punya kaprodi
            $table->foreignId('kaprodi_id')->nullable()->after('nama')->constrained('dosen')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodi', function (Blueprint $table) {
            $table->dropForeign(['kaprodi_id']);
            $table->dropColumn('kaprodi_id');
        });
    }
};

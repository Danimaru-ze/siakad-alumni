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
        Schema::table('materi', function (Blueprint $table) {
            // Change the length of the file_type column to 100
            $table->string('file_type', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            // Revert the length of the file_type column back to 50
            $table->string('file_type', 50)->nullable()->change();
        });
    }
};
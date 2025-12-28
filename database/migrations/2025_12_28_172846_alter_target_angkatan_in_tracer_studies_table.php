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
        Schema::table('tracer_studies', function (Blueprint $table) {
            $table->string('target_angkatan')->nullable()->comment('Target angkatan, null berarti semua angkatan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracer_studies', function (Blueprint $table) {
            $table->string('target_angkatan')->nullable(false)->comment('')->change();
        });
    }
};

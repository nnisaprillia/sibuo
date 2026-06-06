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
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->string('kode_ujian')->nullable()->after('nama_bank');
            $table->datetime('kode_generated_at')->nullable()->after('kode_ujian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->dropColumn(['kode_ujian', 'kode_generated_at']);
        });
    }
};

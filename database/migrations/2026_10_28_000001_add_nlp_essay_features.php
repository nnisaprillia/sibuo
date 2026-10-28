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
        Schema::table('soal', function (Blueprint $table) {
            $table->text('kata_kunci')->nullable()->after('jawaban_benar');
        });

        Schema::table('jawaban_siswa', function (Blueprint $table) {
            $table->decimal('skor_rekomendasi', 5, 2)->nullable()->after('skor');
            $table->decimal('similarity_score', 5, 2)->nullable()->after('skor_rekomendasi');
            $table->decimal('keyword_score', 5, 2)->nullable()->after('similarity_score');
            $table->text('matched_keywords')->nullable()->after('keyword_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn('kata_kunci');
        });

        Schema::table('jawaban_siswa', function (Blueprint $table) {
            $table->dropColumn(['skor_rekomendasi', 'similarity_score', 'keyword_score', 'matched_keywords']);
        });
    }
};

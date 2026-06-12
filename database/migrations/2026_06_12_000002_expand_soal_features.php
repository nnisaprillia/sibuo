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
            $table->text('pilihan_e')->nullable()->after('pilihan_d');
            $table->enum('tipe', ['pg', 'essay', 'tf'])->default('pg')->after('bank_soal_id');
            // Change enum to string to support more options and essay
            $table->string('jawaban_benar')->change();
            
            // Make options nullable for essay type
            $table->text('pilihan_a')->nullable()->change();
            $table->text('pilihan_b')->nullable()->change();
            $table->text('pilihan_c')->nullable()->change();
            $table->text('pilihan_d')->nullable()->change();
        });

        Schema::table('jawaban_siswa', function (Blueprint $table) {
            // Change enum to text to support essay answers
            $table->text('jawaban_siswa')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn(['pilihan_e', 'tipe']);
            // Reverting types to enum is tricky in Laravel without raw SQL or Doctrine DBAL
            // but we'll try to at least revert the nullable status if possible
        });
    }
};

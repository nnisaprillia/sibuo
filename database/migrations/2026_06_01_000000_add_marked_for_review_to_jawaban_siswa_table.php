<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jawaban_siswa', function (Blueprint $table) {
            $table->boolean('marked_for_review')->default(false)->after('is_benar');
        });
    }

    public function down()
    {
        Schema::table('jawaban_siswa', function (Blueprint $table) {
            $table->dropColumn('marked_for_review');
        });
    }
};

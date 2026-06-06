<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa';
    protected $fillable = ['ujian_id', 'soal_id', 'jawaban_siswa', 'is_benar', 'marked_for_review'];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}

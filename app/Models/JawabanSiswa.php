<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa';
    protected $fillable = [
        'ujian_id',
        'soal_id',
        'jawaban_siswa',
        'is_benar',
        'skor',
        'skor_rekomendasi',
        'similarity_score',
        'keyword_score',
        'matched_keywords',
        'marked_for_review'
    ];

    protected $casts = [
        'matched_keywords' => 'array',
        'skor' => 'float',
        'skor_rekomendasi' => 'float',
        'similarity_score' => 'float',
        'keyword_score' => 'float',
        'is_benar' => 'boolean',
        'marked_for_review' => 'boolean',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}

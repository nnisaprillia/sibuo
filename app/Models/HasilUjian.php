<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    protected $table = 'hasil_ujian';
    protected $fillable = ['ujian_id', 'nilai', 'waktu_penyelesaian'];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}

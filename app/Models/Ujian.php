<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = 'ujian';
    protected $fillable = ['siswa_id', 'bank_soal_id', 'kode_ujian', 'waktu_mulai', 'waktu_selesai', 'status'];
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class);
    }

    public function hasilUjian()
    {
        return $this->hasOne(HasilUjian::class);
    }
}

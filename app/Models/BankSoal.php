<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $table = 'bank_soal';
    protected $fillable = ['mata_pelajaran_id', 'kelas_id', 'guru_id', 'nama_bank', 'kode_ujian', 'kode_generated_at', 'durasi', 'jadwal_mulai', 'jadwal_selesai'];
    protected $casts = [
        'jadwal_mulai' => 'datetime',
        'jadwal_selesai' => 'datetime',
        'kode_generated_at' => 'datetime',
    ];

    /**
     * Get the current exam code or regenerate if expired (1 minute).
     */
    public function getOrGenerateKodeUjian()
    {
        $now = now();
        
        // If the exam schedule has ended, do not regenerate the code
        if ($this->jadwal_selesai && $now->greaterThan($this->jadwal_selesai)) {
            return $this->kode_ujian;
        }

        // If code doesn't exist or is older than 1 minute, regenerate
        if (!$this->kode_ujian || !$this->kode_generated_at || $this->kode_generated_at->diffInMinutes($now) >= 1) {
            $this->kode_ujian = $this->generateNewKode();
            $this->kode_generated_at = $now;
            $this->save();
        }
        
        return $this->kode_ujian;
    }

    /**
     * Generate a random code with format XXX-XXX.
     */
    protected function generateNewKode()
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Exclude ambiguous chars like 0, O, 1, I
        $part1 = '';
        $part2 = '';
        
        for ($i = 0; $i < 3; $i++) {
            $part1 .= $chars[rand(0, strlen($chars) - 1)];
            $part2 .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $part1 . '-' . $part2;
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function soal()
    {
        return $this->hasMany(Soal::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }
}

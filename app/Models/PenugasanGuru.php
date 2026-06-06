<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanGuru extends Model
{
    protected $table = 'penugasan_guru';
    protected $fillable = ['guru_id', 'mata_pelajaran_id', 'kelas_id'];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $fillable = ['nama', 'deskripsi'];

    public function penugasanGuru()
    {
        return $this->hasMany(PenugasanGuru::class);
    }

    public function bankSoal()
    {
        return $this->hasMany(BankSoal::class);
    }
}

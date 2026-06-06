<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'tingkat'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function penugasanGuru()
    {
        return $this->hasMany(PenugasanGuru::class);
    }
}

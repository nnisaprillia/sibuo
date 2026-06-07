<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PenugasanGuru;
use Illuminate\Database\Seeder;

class PenugasanGuruSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = User::where('role', 'guru')->get();
        $mapels = MataPelajaran::all();
        $kelas = Kelas::all();

        foreach ($gurus as $guru) {
            // Assign 2 mapel to each guru
            $assignedMapels = $mapels->random(2);
            foreach ($assignedMapels as $mapel) {
                // Assign to 3 random kelas
                $assignedKelas = $kelas->random(3);
                foreach ($assignedKelas as $k) {
                    \DB::table('penugasan_guru')->insert([
                        'guru_id' => $guru->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'kelas_id' => $k->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@sibuo.test'],
            [
                'name' => 'Administrator SIBUO',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Guru
        $gurus = [
            ['name' => 'Budi Santoso, S.Pd', 'email' => 'budi@guru.test'],
            ['name' => 'Siti Aminah, M.Pd', 'email' => 'siti@guru.test'],
            ['name' => 'Agus Hermawan, S.Kom', 'email' => 'agus@guru.test'],
            ['name' => 'Lani Wijaya, S.Si', 'email' => 'lani@guru.test'],
        ];

        foreach ($gurus as $g) {
            User::updateOrCreate(
                ['email' => $g['email']],
                [
                    'name' => $g['name'],
                    'password' => Hash::make('password'),
                    'role' => 'guru',
                ]
            );
        }

        // Siswa
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();

        foreach ($kelas as $k) {
            $j_id = ($k->tingkat === 'SMA') ? $jurusan->random()->id : null;
            
            for ($i = 1; $i <= 5; $i++) {
                $nama_kelas_clean = str_replace(' ', '', strtolower($k->nama_kelas));
                User::updateOrCreate(
                    ['email' => "siswa{$i}.{$nama_kelas_clean}@siswa.test"],
                    [
                        'name' => "Siswa $i " . $k->nama_kelas,
                        'password' => Hash::make('password'),
                        'role' => 'siswa',
                        'kelas_id' => $k->id,
                        'jurusan_id' => $j_id,
                    ]
                );
            }
        }
    }
}

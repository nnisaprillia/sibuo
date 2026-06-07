<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            // SD
            ['nama_kelas' => 'Kelas 1 SD', 'tingkat' => 'SD'],
            ['nama_kelas' => 'Kelas 2 SD', 'tingkat' => 'SD'],
            ['nama_kelas' => 'Kelas 3 SD', 'tingkat' => 'SD'],
            ['nama_kelas' => 'Kelas 4 SD', 'tingkat' => 'SD'],
            ['nama_kelas' => 'Kelas 5 SD', 'tingkat' => 'SD'],
            ['nama_kelas' => 'Kelas 6 SD', 'tingkat' => 'SD'],
            // SMP
            ['nama_kelas' => 'Kelas 7 SMP', 'tingkat' => 'SMP'],
            ['nama_kelas' => 'Kelas 8 SMP', 'tingkat' => 'SMP'],
            ['nama_kelas' => 'Kelas 9 SMP', 'tingkat' => 'SMP'],
            // SMA
            ['nama_kelas' => 'Kelas 10 IPA', 'tingkat' => 'SMA'],
            ['nama_kelas' => 'Kelas 10 IPS', 'tingkat' => 'SMA'],
            ['nama_kelas' => 'Kelas 11 IPA', 'tingkat' => 'SMA'],
            ['nama_kelas' => 'Kelas 11 IPS', 'tingkat' => 'SMA'],
            ['nama_kelas' => 'Kelas 12 IPA', 'tingkat' => 'SMA'],
            ['nama_kelas' => 'Kelas 12 IPS', 'tingkat' => 'SMA'],
        ];

        foreach ($kelas as $k) {
            \App\Models\Kelas::updateOrCreate(
                ['nama_kelas' => $k['nama_kelas']],
                $k
            );
        }
    }
}

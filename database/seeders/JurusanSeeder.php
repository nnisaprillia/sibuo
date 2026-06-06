<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = [
            ['nama_jurusan' => 'IPA'],
            ['nama_jurusan' => 'IPS'],
            ['nama_jurusan' => 'Bahasa'],
        ];

        foreach ($jurusan as $j) {
            \App\Models\Jurusan::create($j);
        }
    }
}

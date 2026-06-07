<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaran = [
            ['nama' => 'Matematika', 'deskripsi' => 'Pelajaran tentang angka dan logika'],
            ['nama' => 'Bahasa Indonesia', 'deskripsi' => 'Pelajaran tentang bahasa nasional'],
            ['nama' => 'Bahasa Inggris', 'deskripsi' => 'Pelajaran tentang bahasa internasional'],
            ['nama' => 'IPA', 'deskripsi' => 'Ilmu Pengetahuan Alam'],
            ['nama' => 'IPS', 'deskripsi' => 'Ilmu Pengetahuan Sosial'],
            ['nama' => 'PKN', 'deskripsi' => 'Pendidikan Kewarganegaraan'],
            ['nama' => 'Agama', 'deskripsi' => 'Pelajaran agama'],
            ['nama' => 'Seni Budaya', 'deskripsi' => 'Pelajaran seni dan budaya'],
            ['nama' => 'Penjas', 'deskripsi' => 'Pendidikan Jasmani dan Olahraga'],
        ];

        foreach ($mataPelajaran as $mp) {
            \App\Models\MataPelajaran::updateOrCreate(
                ['nama' => $mp['nama']],
                $mp
            );
        }
    }
}

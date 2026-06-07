<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BankSoal;
use App\Models\Soal;
use App\Models\MataPelajaran;
use App\Models\Ujian;
use App\Models\HasilUjian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = User::where('role', 'guru')->get();
        $mapels = MataPelajaran::all();

        foreach ($gurus as $guru) {
            // Each guru makes 1 bank soal
            $mapel = $mapels->random();
            $bank = BankSoal::create([
                'mata_pelajaran_id' => $mapel->id,
                'guru_id' => $guru->id,
                'nama_bank' => "Ujian Akhir " . $mapel->nama . " - " . $guru->name,
                'durasi' => 60,
                'jadwal_mulai' => now()->subDays(rand(1, 5)),
                'jadwal_selesai' => now()->addDays(rand(1, 5)),
                'kode_ujian' => strtoupper(Str::random(6)),
            ]);

            // Create 40 questions for each bank
            for ($i = 1; $i <= 40; $i++) {
                Soal::create([
                    'bank_soal_id' => $bank->id,
                    'pertanyaan' => "Ini adalah pertanyaan ke-$i untuk mata pelajaran " . $mapel->nama . ". Apa jawaban yang benar?",
                    'pilihan_a' => "Pilihan Jawaban A untuk soal $i",
                    'pilihan_b' => "Pilihan Jawaban B untuk soal $i",
                    'pilihan_c' => "Pilihan Jawaban C untuk soal $i",
                    'pilihan_d' => "Pilihan Jawaban D untuk soal $i",
                    'jawaban_benar' => ['a', 'b', 'c', 'd'][rand(0, 3)],
                ]);
            }

            // Simulate students taking the exam
            $students = User::where('role', 'siswa')->inRandomOrder()->take(5)->get();
            foreach ($students as $siswa) {
                $ujian = Ujian::create([
                    'siswa_id' => $siswa->id,
                    'bank_soal_id' => $bank->id,
                    'kode_ujian' => $bank->kode_ujian,
                    'waktu_mulai' => $bank->jadwal_mulai->addMinutes(rand(1, 60)),
                    'waktu_selesai' => null,
                    'status' => 'completed',
                ]);

                $ujian->update(['waktu_selesai' => $ujian->waktu_mulai->addMinutes(rand(20, 50))]);

                $nilai = rand(60, 100);
                HasilUjian::create([
                    'ujian_id' => $ujian->id,
                    'nilai' => $nilai,
                    'waktu_penyelesaian' => $ujian->waktu_mulai->diffInSeconds($ujian->waktu_selesai),
                ]);

                // Fill Jawaban Siswa
                foreach ($bank->soal as $soal) {
                    \DB::table('jawaban_siswa')->insert([
                        'ujian_id' => $ujian->id,
                        'soal_id' => $soal->id,
                        'jawaban_siswa' => ['a', 'b', 'c', 'd'][rand(0, 3)],
                        'is_benar' => rand(0, 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}

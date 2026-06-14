<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\HasilUjian;
use App\Models\JawabanSiswa;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UjianController extends Controller
{
    public function index()
    {
        $siswa = Auth::user();
        $now = now();

        $availableBankSoal = BankSoal::with('mataPelajaran', 'guru')
            ->where('is_published', true)
            ->where('jadwal_mulai', '<=', $now)
            ->where('jadwal_selesai', '>=', $now)
            ->whereExists(function ($query) use ($siswa) {
                $query->select(DB::raw(1))
                    ->from('penugasan_guru')
                    ->whereColumn('penugasan_guru.mata_pelajaran_id', 'bank_soal.mata_pelajaran_id');

                if ($siswa->kelas_id) {
                    $query->where('penugasan_guru.kelas_id', $siswa->kelas_id);
                }
            })
            ->get();

        $examMap = Ujian::where('siswa_id', $siswa->id)
            ->with('bankSoal')
            ->get()
            ->keyBy('bank_soal_id');

        $history = Ujian::where('siswa_id', $siswa->id)
            ->with('bankSoal.mataPelajaran', 'hasilUjian')
            ->orderByDesc('created_at')
            ->paginate(15);

        // upcoming bank soal for today and tomorrow
        $upcomingBankSoal = BankSoal::with('mataPelajaran', 'guru')
            ->where('is_published', true)
            ->where('jadwal_mulai', '>', $now)
            ->whereExists(function ($query) use ($siswa) {
                $query->select(DB::raw(1))
                    ->from('penugasan_guru')
                    ->whereColumn('penugasan_guru.mata_pelajaran_id', 'bank_soal.mata_pelajaran_id');

                if ($siswa->kelas_id) {
                    $query->where('penugasan_guru.kelas_id', $siswa->kelas_id);
                }
            })
            ->orderBy('jadwal_mulai', 'asc')
            ->get();

        return view('siswa.ujian.index', compact('availableBankSoal', 'examMap', 'history', 'upcomingBankSoal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_soal_id' => 'required|exists:bank_soal,id',
            'kode_ujian' => 'required|string',
        ]);

        $bankSoal = BankSoal::findOrFail($request->bank_soal_id);

        // Validasi Kode Ujian (Regenerate if needed)
        $currentCode = $bankSoal->getOrGenerateKodeUjian();
        if (strtoupper($request->kode_ujian) !== strtoupper($currentCode)) {
            return redirect()->back()->with('error', 'Kode ujian tidak valid atau sudah kedaluwarsa. Silakan minta kode terbaru kepada pengawas/guru.');
        }

        if (now()->lt($bankSoal->jadwal_mulai) || now()->gt($bankSoal->jadwal_selesai)) {
            return redirect()->back()->with('error', 'Ujian tidak tersedia saat ini.');
        }

        $existing = Ujian::where('siswa_id', Auth::id())
            ->where('bank_soal_id', $bankSoal->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'ongoing') {
                return redirect()->route('siswa.ujian.show', $existing)->with('info', 'Lanjutkan ujian yang sudah dimulai.');
            }

            return redirect()->back()->with('error', 'Anda sudah mengikuti ujian ini.');
        }

        $ujian = Ujian::create([
            'siswa_id' => Auth::id(),
            'bank_soal_id' => $bankSoal->id,
            'kode_ujian' => $currentCode,
            'waktu_mulai' => now(),
            'status' => 'ongoing',
        ]);

        return redirect()->route('siswa.ujian.show', $ujian)->with('success', 'Ujian dimulai. Kerjakan soal sampai selesai.');
    }

    public function saveAnswer(Request $request, Ujian $ujian)
    {
        $this->ensureBelongsToStudent($ujian);

        if ($ujian->status !== 'ongoing') {
            return response()->json(['error' => 'Ujian sudah selesai'], 403);
        }

        $request->validate([
            'soal_id' => 'required|exists:soal,id',
            'jawaban' => 'nullable|string',
            'marked' => 'nullable',
        ]);

        $soal = Soal::findOrFail($request->soal_id);
        
        // Auto-grade only for non-essay questions
        $isBenar = false;
        $skor = 0;
        if ($soal->tipe !== 'essay' && $request->jawaban !== null) {
            $isBenar = $request->jawaban === $soal->jawaban_benar;
            $skor = $isBenar ? 1 : 0;
        }

        JawabanSiswa::updateOrCreate(
            ['ujian_id' => $ujian->id, 'soal_id' => $soal->id],
            [
                'jawaban_siswa' => $request->jawaban,
                'is_benar' => $isBenar,
                'skor' => $skor,
                'marked_for_review' => filter_var($request->marked, FILTER_VALIDATE_BOOLEAN),
            ]
        );

        return response()->json(['success' => true]);
    }

    public function recordViolation(Request $request, Ujian $ujian)
    {
        $this->ensureBelongsToStudent($ujian);

        if ($ujian->status !== 'ongoing') {
            return response()->json(['error' => 'Ujian sudah selesai'], 403);
        }

        $ujian->increment('pelanggaran');

        return response()->json([
            'success' => true,
            'total_pelanggaran' => $ujian->pelanggaran
        ]);
    }

    public function verifyCode(Request $request, Ujian $ujian)
    {
        $this->ensureBelongsToStudent($ujian);

        $request->validate([
            'kode_ujian' => 'required|string',
        ]);

        $bankSoal = $ujian->bankSoal;
        $currentCode = $bankSoal->getOrGenerateKodeUjian();

        if (strtoupper($request->kode_ujian) === strtoupper($currentCode)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 422);
    }

    public function show(Ujian $ujian)
    {
        $this->ensureBelongsToStudent($ujian);

        $ujian->load('bankSoal.mataPelajaran', 'bankSoal.guru', 'bankSoal.soal', 'jawabanSiswa.soal', 'hasilUjian');

        // Randomisasi soal per siswa (consistent order using ujian_id as seed)
        $randomizedSoal = $ujian->bankSoal->soal->shuffle($ujian->id);
        $ujian->bankSoal->setRelation('soal', $randomizedSoal);

        if ($ujian->status === 'ongoing') {
            $expireAt = $ujian->waktu_mulai->copy()->addMinutes($ujian->bankSoal->durasi);
            if (now()->greaterThan($expireAt)) {
                $this->finishExam($ujian);
                $ujian->refresh();
                $ujian->load('bankSoal.mataPelajaran', 'bankSoal.soal', 'jawabanSiswa.soal', 'hasilUjian');
            }
        }

        $answerMap = $ujian->jawabanSiswa->pluck('jawaban_siswa', 'soal_id')->all();
        $markedMap = $ujian->jawabanSiswa->pluck('marked_for_review', 'soal_id')->all();

        $expireAt = $ujian->waktu_mulai->copy()->addMinutes($ujian->bankSoal->durasi);

        return view('siswa.ujian.show', compact('ujian', 'answerMap', 'markedMap', 'expireAt'));
    }

    public function submit(Request $request, Ujian $ujian)
    {
        $this->ensureBelongsToStudent($ujian);

        if ($ujian->status !== 'ongoing') {
            return redirect()->route('siswa.ujian.show', $ujian)->with('error', 'Ujian sudah selesai.');
        }

        $ujian->load('bankSoal.soal');

        $rules = [];
        foreach ($ujian->bankSoal->soal as $soal) {
            $rules["answers.{$soal->id}"] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);
        $marked = $request->input('marked', []);

        DB::transaction(function () use ($ujian, $validated, $marked) {
            $totalSkor = 0;

            foreach ($ujian->bankSoal->soal as $soal) {
                $jawaban = $validated['answers'][$soal->id] ?? null;
                
                $isBenar = false;
                $skor = 0;
                if ($soal->tipe !== 'essay' && $jawaban !== null) {
                    $isBenar = $jawaban === $soal->jawaban_benar;
                    $skor = $isBenar ? 1 : 0;
                }
                
                $totalSkor += $skor;
                $isMarked = isset($marked[$soal->id]) && in_array($marked[$soal->id], ['1', 1, 'true', true], true);

                JawabanSiswa::updateOrCreate(
                    ['ujian_id' => $ujian->id, 'soal_id' => $soal->id],
                    [
                        'jawaban_siswa' => $jawaban,
                        'is_benar' => $isBenar,
                        'skor' => $skor,
                        'marked_for_review' => $isMarked,
                    ]
                );
            }

            $score = $ujian->bankSoal->soal->count() > 0
                ? round(($totalSkor / $ujian->bankSoal->soal->count()) * 100, 2)
                : 0;

            $ujian->update([
                'status' => 'completed',
                'waktu_selesai' => now(),
            ]);

            HasilUjian::create([
                'ujian_id' => $ujian->id,
                'nilai' => $score,
                'waktu_penyelesaian' => abs(now()->diffInSeconds($ujian->waktu_mulai)),
            ]);
        });

        return redirect()->route('siswa.ujian.show', $ujian)->with('success', 'Ujian selesai, nilai Anda telah tersimpan.');
    }

    protected function ensureBelongsToStudent(Ujian $ujian)
    {
        if ($ujian->siswa_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }

    protected function finishExam(Ujian $ujian)
    {
        $ujian->load('bankSoal.soal', 'jawabanSiswa');

        if ($ujian->status !== 'ongoing') {
            return;
        }

        $totalSkor = 0;
        $questions = $ujian->bankSoal->soal;
        $answers = $ujian->jawabanSiswa->keyBy('soal_id');

        foreach ($questions as $soal) {
            $skor = 0;
            if ($soal->tipe !== 'essay') {
                if (isset($answers[$soal->id]) && $answers[$soal->id]->jawaban_siswa === $soal->jawaban_benar) {
                    $skor = 1;
                }
            } else {
                // For essay, if already graded, keep the score (though finishExam usually happens before grading)
                $skor = isset($answers[$soal->id]) ? $answers[$soal->id]->skor : 0;
            }
            $totalSkor += $skor;

            // Also update the record if it exists to ensure skor is set
            if (isset($answers[$soal->id])) {
                $answers[$soal->id]->update(['skor' => $skor]);
            }
        }

        $score = $questions->count() > 0
            ? round(($totalSkor / $questions->count()) * 100, 2)
            : 0;

        $ujian->update([
            'status' => 'completed',
            'waktu_selesai' => now(),
        ]);

        HasilUjian::create([
            'ujian_id' => $ujian->id,
            'nilai' => $score,
            'waktu_penyelesaian' => abs(now()->diffInSeconds($ujian->waktu_mulai)),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\HasilUjian;
use App\Models\JawabanSiswa;
use App\Models\Soal;
use App\Models\Ujian;
use App\Services\EssayScoringService;
use App\Services\FisherYatesService;
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
        
        $isBenar = false;
        $skor = 0;
        $skorRekomendasi = null;
        $similarityScore = null;
        $keywordScore = null;
        $matchedKeywords = null;

        if ($soal->tipe !== 'essay' && $request->jawaban !== null) {
            $isBenar = $request->jawaban === $soal->jawaban_benar;
            $skor = $isBenar ? 1 : 0;
        } elseif ($soal->tipe === 'essay' && !empty($request->jawaban)) {
            $essayService = new EssayScoringService();
            $eval = $essayService->evaluate(
                $request->jawaban,
                $soal->jawaban_benar,
                $soal->kata_kunci
            );
            $skorRekomendasi = $eval['final_suggested_score'];
            $similarityScore = $eval['similarity_score'];
            $keywordScore = $eval['keyword_score'];
            $matchedKeywords = $eval['matched_keywords'];
            $skor = $skorRekomendasi;
            $isBenar = $skor >= 0.7;
        }

        JawabanSiswa::updateOrCreate(
            ['ujian_id' => $ujian->id, 'soal_id' => $soal->id],
            [
                'jawaban_siswa' => $request->jawaban,
                'is_benar' => $isBenar,
                'skor' => $skor,
                'skor_rekomendasi' => $skorRekomendasi,
                'similarity_score' => $similarityScore,
                'keyword_score' => $keywordScore,
                'matched_keywords' => $matchedKeywords,
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

        // Randomisasi urutan soal per siswa menggunakan Algoritma Fisher-Yates O(n)
        $soalArray = $ujian->bankSoal->soal->all();
        $randomizedSoal = FisherYatesService::shuffle($soalArray, (int) $ujian->id);
        $ujian->bankSoal->setRelation('soal', collect($randomizedSoal));

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

            $essayService = new EssayScoringService();

            foreach ($ujian->bankSoal->soal as $soal) {
                $jawaban = $validated['answers'][$soal->id] ?? null;
                
                $isBenar = false;
                $skor = 0;
                $skorRekomendasi = null;
                $similarityScore = null;
                $keywordScore = null;
                $matchedKeywords = null;

                if ($soal->tipe !== 'essay' && $jawaban !== null) {
                    $isBenar = $jawaban === $soal->jawaban_benar;
                    $skor = $isBenar ? 1 : 0;
                } elseif ($soal->tipe === 'essay' && !empty($jawaban)) {
                    $eval = $essayService->evaluate(
                        $jawaban,
                        $soal->jawaban_benar,
                        $soal->kata_kunci
                    );
                    $skorRekomendasi = $eval['final_suggested_score'];
                    $similarityScore = $eval['similarity_score'];
                    $keywordScore = $eval['keyword_score'];
                    $matchedKeywords = $eval['matched_keywords'];
                    $skor = $skorRekomendasi;
                    $isBenar = $skor >= 0.7;
                }
                
                $totalSkor += $skor;
                $isMarked = isset($marked[$soal->id]) && in_array($marked[$soal->id], ['1', 1, 'true', true], true);

                JawabanSiswa::updateOrCreate(
                    ['ujian_id' => $ujian->id, 'soal_id' => $soal->id],
                    [
                        'jawaban_siswa' => $jawaban,
                        'is_benar' => $isBenar,
                        'skor' => $skor,
                        'skor_rekomendasi' => $skorRekomendasi,
                        'similarity_score' => $similarityScore,
                        'keyword_score' => $keywordScore,
                        'matched_keywords' => $matchedKeywords,
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
        $essayService = new EssayScoringService();

        foreach ($questions as $soal) {
            $skor = 0;
            $jawabanObj = $answers[$soal->id] ?? null;
            $jawabanText = $jawabanObj?->jawaban_siswa;

            if ($soal->tipe !== 'essay') {
                if ($jawabanObj && $jawabanText === $soal->jawaban_benar) {
                    $skor = 1;
                }
            } else {
                if ($jawabanObj && $jawabanObj->skor > 0) {
                    // Jika sudah dinilai sebelumnya, pertahankan
                    $skor = $jawabanObj->skor;
                } elseif (!empty($jawabanText)) {
                    // Jalankan evaluasi NLP hybrid jika belum ada skor
                    $eval = $essayService->evaluate(
                        $jawabanText,
                        $soal->jawaban_benar,
                        $soal->kata_kunci
                    );
                    $skor = $eval['final_suggested_score'];
                    $jawabanObj?->update([
                        'skor' => $skor,
                        'skor_rekomendasi' => $eval['final_suggested_score'],
                        'similarity_score' => $eval['similarity_score'],
                        'keyword_score' => $eval['keyword_score'],
                        'matched_keywords' => $eval['matched_keywords'],
                        'is_benar' => $skor >= 0.7,
                    ]);
                }
            }
            $totalSkor += $skor;

            if ($jawabanObj && $soal->tipe !== 'essay') {
                $jawabanObj->update(['skor' => $skor]);
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

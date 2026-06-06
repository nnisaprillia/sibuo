<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\HasilUjian;
use App\Models\JawabanSiswa;
use App\Models\Ujian;
use App\Models\PenugasanGuru;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HasilUjianController extends Controller
{
    /**
     * Display a listing of exam results for the guru.
     */
    public function index()
    {
        $guru = Auth::user();
        
        // Get all exam results for bank soal created by this guru
        $hasilUjians = HasilUjian::whereHas('ujian', function ($query) use ($guru) {
            $query->whereHas('bankSoal', function ($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            });
        })
            ->with('ujian.siswa', 'ujian.bankSoal')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('guru.hasil-ujian.index', compact('hasilUjians'));
    }

    /**
     * Show detailed results for a specific exam.
     */
    public function show(Ujian $ujian)
    {
        $guru = Auth::user();
        
        // Verify that this exam's bank soal belongs to this guru
        if ($ujian->bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $ujian->load('siswa', 'bankSoal.mataPelajaran', 'jawabanSiswa.soal', 'hasilUjian');
        
        // Get all questions with student answers
        $questions = $ujian->jawabanSiswa()
            ->with('soal')
            ->get();

        return view('guru.hasil-ujian.show', compact('ujian', 'questions'));
    }

    /**
     * Display results grouped by Bank Soal.
     */
    public function byBankSoal(BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership of bank soal
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $bankSoal->load('mataPelajaran');
        
        // Get all exams for this bank soal with results
        $ujians = Ujian::where('bank_soal_id', $bankSoal->id)
            ->with('siswa', 'hasilUjian')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate statistics
        $totalExams = $ujians->total();
        $completedExams = Ujian::where('bank_soal_id', $bankSoal->id)
            ->whereHas('hasilUjian')
            ->count();
        
        $averageScore = HasilUjian::whereHas('ujian', function ($query) use ($bankSoal) {
            $query->where('bank_soal_id', $bankSoal->id);
        })->avg('nilai');

        return view('guru.hasil-ujian.by-bank-soal', compact(
            'bankSoal',
            'ujians',
            'totalExams',
            'completedExams',
            'averageScore'
        ));
    }

    /**
     * Display answer statistics for analysis.
     */
    public function statistics(BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership of bank soal
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $bankSoal->load('mataPelajaran', 'soal');
        
        // Get statistics for each question
        $questionStats = [];
        foreach ($bankSoal->soal as $soal) {
            $totalAnswers = JawabanSiswa::where('soal_id', $soal->id)->count();
            $correctAnswers = JawabanSiswa::where('soal_id', $soal->id)
                ->where('is_benar', true)
                ->count();
            
            $questionStats[] = [
                'soal' => $soal,
                'total_answers' => $totalAnswers,
                'correct_answers' => $correctAnswers,
                'percentage' => $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 2) : 0,
            ];
        }

        // compute completed and pending students for this bank soal
        $completedUjians = Ujian::where('bank_soal_id', $bankSoal->id)
            ->where('status', 'completed')
            ->with('siswa')
            ->get();

        $completedStudentIds = $completedUjians->pluck('siswa_id')->unique()->all();

        // find assigned kelas via penugasan for this guru and mata pelajaran
        $kelasIds = PenugasanGuru::where('guru_id', $guru->id)
            ->where('mata_pelajaran_id', $bankSoal->mata_pelajaran_id)
            ->pluck('kelas_id')
            ->unique()
            ->all();

        $assignedStudents = User::whereIn('kelas_id', $kelasIds)->where('role', 'siswa')->get();

        $pendingStudents = $assignedStudents->filter(function($s) use ($completedStudentIds) {
            return !in_array($s->id, $completedStudentIds);
        })->values();

        // map completed ujian by student id for quick link lookup
        $completedMap = $completedUjians->keyBy('siswa_id');

        return view('guru.hasil-ujian.statistics', compact('bankSoal', 'questionStats', 'completedUjians', 'completedMap', 'assignedStudents', 'pendingStudents'));
    }
}

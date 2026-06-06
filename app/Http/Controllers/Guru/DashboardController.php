<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\PenugasanGuru;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the guru dashboard.
     */
    public function index()
    {
        $guru = Auth::user();
        
        // Get assigned mata pelajaran
        $penugasan = PenugasanGuru::where('guru_id', $guru->id)
            ->with('mataPelajaran', 'kelas')
            ->get();
        
        // Get bank soal created by this guru
        $bankSoals = BankSoal::where('guru_id', $guru->id)
            ->with('mataPelajaran')
            ->get();
            
        // Pre-generate/refresh exam codes
        foreach ($bankSoals as $bank) {
            $bank->getOrGenerateKodeUjian();
        }
        
        // Get upcoming exams (bank soal with schedule in the future)
        $upcomingExams = BankSoal::where('guru_id', $guru->id)
            ->where('jadwal_mulai', '>', now())
            ->with('mataPelajaran')
            ->orderBy('jadwal_mulai')
            ->take(5)
            ->get();
        
        // Get statistics
        $totalBankSoal = $bankSoals->count();
        $totalSoal = BankSoal::where('guru_id', $guru->id)
            ->with('soal')
            ->get()
            ->sum(function ($bank) {
                return $bank->soal->count();
            });
        
        $totalExams = BankSoal::where('guru_id', $guru->id)
            ->with('ujian')
            ->get()
            ->sum(function ($bank) {
                return $bank->ujian->count();
            });

        return view('guru.dashboard', compact(
            'penugasan',
            'bankSoals',
            'upcomingExams',
            'totalBankSoal',
            'totalSoal',
            'totalExams'
        ));
    }
}

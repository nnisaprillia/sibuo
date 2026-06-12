<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\MataPelajaran;
use App\Models\PenugasanGuru;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSoalController extends Controller
{
    /**
     * Display a listing of the Bank Soal.
     */
    public function index()
    {
        $guru = Auth::user();
        
        // Get all bank soal created by this guru
        $bankSoals = BankSoal::where('guru_id', $guru->id)
            ->with('mataPelajaran', 'kelas', 'soal')
            ->paginate(10);

        // Check if guru has any assignments
        $hasAssignments = PenugasanGuru::where('guru_id', $guru->id)->exists();

        return view('guru.bank-soal.index', compact('bankSoals', 'hasAssignments'));
    }

    /**
     * Show the form for creating a new Bank Soal.
     */
    public function create()
    {
        $guru = Auth::user();
        
        // Get mata pelajaran that are assigned to this guru
        $mataPelajarans = MataPelajaran::whereHas('penugasanGuru', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id);
        })->get();

        // Get classes assigned to this guru
        $kelases = \App\Models\Kelas::whereHas('penugasanGuru', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id);
        })->get();

        $warningNoAssignment = false;
        if ($mataPelajarans->isEmpty()) {
            $mataPelajarans = MataPelajaran::all();
            $kelases = \App\Models\Kelas::all();
            $warningNoAssignment = true;
        }

        return view('guru.bank-soal.create', compact('mataPelajarans', 'kelases', 'warningNoAssignment'));
    }

    /**
     * Store a newly created Bank Soal in storage.
     */
    public function store(Request $request)
    {
        $guru = Auth::user();
        
        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_bank' => 'required|string|max:255',
            'durasi' => 'required|integer|min:1|max:480',
            'jadwal_mulai' => 'required|date',
            'jadwal_selesai' => 'required|date|after:jadwal_mulai',
        ]);

        // Verify that this guru is assigned to this mata pelajaran and class
        $assigned = PenugasanGuru::where('guru_id', $guru->id)
            ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->exists();

        if (!$assigned && PenugasanGuru::where('guru_id', $guru->id)->exists()) {
            return back()->with('error', 'Anda tidak ditugaskan pada mata pelajaran dan kelas ini.');
        }

        $validated['guru_id'] = $guru->id;
        $bankSoal = BankSoal::create($validated);

        return redirect()->route('guru.bank-soal.show', $bankSoal->id)
            ->with('success', 'Bank Soal berhasil dibuat.');
    }

    /**
     * Display the specified Bank Soal with its questions.
     */
    public function show(BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $bankSoal->load('mataPelajaran', 'soal');
        $soals = $bankSoal->soal()->paginate(10);

        return view('guru.bank-soal.show', compact('bankSoal', 'soals'));
    }

    /**
     * Show the form for editing the Bank Soal.
     */
    public function edit(BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        // Get mata pelajaran that are assigned to this guru
        $mataPelajarans = MataPelajaran::whereHas('penugasanGuru', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id);
        })->get();

        // Get classes assigned to this guru
        $kelases = \App\Models\Kelas::whereHas('penugasanGuru', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id);
        })->get();

        $warningNoAssignment = false;
        if ($mataPelajarans->isEmpty()) {
            $mataPelajarans = MataPelajaran::all();
            $kelases = \App\Models\Kelas::all();
            $warningNoAssignment = true;
        }

        return view('guru.bank-soal.edit', compact('bankSoal', 'mataPelajarans', 'kelases', 'warningNoAssignment'));
    }

    /**
     * Update the specified Bank Soal in storage.
     */
    public function update(Request $request, BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_bank' => 'required|string|max:255',
            'durasi' => 'required|integer|min:1|max:480',
            'jadwal_mulai' => 'required|date',
            'jadwal_selesai' => 'required|date|after:jadwal_mulai',
        ]);

        // Verify that this guru is assigned to this mata pelajaran and class
        $assigned = PenugasanGuru::where('guru_id', $guru->id)
            ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->exists();

        if (!$assigned && PenugasanGuru::where('guru_id', $guru->id)->exists()) {
            return back()->with('error', 'Anda tidak ditugaskan pada mata pelajaran dan kelas ini.');
        }

        $bankSoal->update($validated);

        return redirect()->route('guru.bank-soal.show', $bankSoal->id)
            ->with('success', 'Bank Soal berhasil diperbarui.');
    }

    /**
     * Remove the specified Bank Soal from storage.
     */
    public function destroy(BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $bankSoal->delete();

        return redirect()->route('guru.bank-soal.index')
            ->with('success', 'Bank Soal berhasil dihapus.');
    }

    /**
     * Toggle the published status of the Bank Soal.
     */
    public function togglePublish(BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $bankSoal->is_published = !$bankSoal->is_published;
        $bankSoal->save();

        $status = $bankSoal->is_published ? 'dipublish' : 'diarsipkan (unpublish)';
        return back()->with('success', "Bank Soal berhasil {$status}.");
    }
}

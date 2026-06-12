<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoalController extends Controller
{
    /**
     * Show the form for creating a new Soal (Question).
     */
    public function create(BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership of bank soal
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $bankSoal->load('mataPelajaran');

        return view('guru.soal.create', compact('bankSoal'));
    }

    /**
     * Store a newly created Soal in storage.
     */
    public function store(Request $request, BankSoal $bankSoal)
    {
        $guru = Auth::user();
        
        // Verify ownership of bank soal
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'tipe' => 'required|in:pg,essay,tf',
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required_if:tipe,pg|nullable|string',
            'pilihan_b' => 'required_if:tipe,pg|nullable|string',
            'pilihan_c' => 'required_if:tipe,pg|nullable|string',
            'pilihan_d' => 'required_if:tipe,pg|nullable|string',
            'pilihan_e' => 'nullable|string',
            'jawaban_benar' => 'required|string',
        ]);

        $validated['bank_soal_id'] = $bankSoal->id;
        $soal = Soal::create($validated);

        return redirect()->route('guru.bank-soal.show', $bankSoal->id)
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the Soal.
     */
    public function edit(BankSoal $bankSoal, Soal $soal)
    {
        $guru = Auth::user();
        
        // Verify ownership of bank soal
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        // Verify soal belongs to this bank soal
        if ($soal->bank_soal_id !== $bankSoal->id) {
            abort(404);
        }

        $bankSoal->load('mataPelajaran');

        return view('guru.soal.edit', compact('bankSoal', 'soal'));
    }

    /**
     * Update the specified Soal in storage.
     */
    public function update(Request $request, BankSoal $bankSoal, Soal $soal)
    {
        $guru = Auth::user();
        
        // Verify ownership of bank soal
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        // Verify soal belongs to this bank soal
        if ($soal->bank_soal_id !== $bankSoal->id) {
            abort(404);
        }

        $validated = $request->validate([
            'tipe' => 'required|in:pg,essay,tf',
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required_if:tipe,pg|nullable|string',
            'pilihan_b' => 'required_if:tipe,pg|nullable|string',
            'pilihan_c' => 'required_if:tipe,pg|nullable|string',
            'pilihan_d' => 'required_if:tipe,pg|nullable|string',
            'pilihan_e' => 'nullable|string',
            'jawaban_benar' => 'required|string',
        ]);

        $soal->update($validated);

        return redirect()->route('guru.bank-soal.show', $bankSoal->id)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Remove the specified Soal from storage.
     */
    public function destroy(BankSoal $bankSoal, Soal $soal)
    {
        $guru = Auth::user();
        
        // Verify ownership of bank soal
        if ($bankSoal->guru_id !== $guru->id) {
            abort(403, 'Unauthorized');
        }

        // Verify soal belongs to this bank soal
        if ($soal->bank_soal_id !== $bankSoal->id) {
            abort(404);
        }

        $soal->delete();

        return redirect()->route('guru.bank-soal.show', $bankSoal->id)
            ->with('success', 'Soal berhasil dihapus.');
    }
}

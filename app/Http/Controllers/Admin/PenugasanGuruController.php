<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenugasanGuru;
use App\Models\User;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Illuminate\Http\Request;

class PenugasanGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penugasans = PenugasanGuru::query()->with(['guru', 'mataPelajaran', 'kelas'])->paginate(10);
        return view('admin.penugasan-guru.index', compact('penugasans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = User::query()->where('role', 'guru')->get();
        $mataPelajarans = MataPelajaran::all();
        $kelas = Kelas::all();
        return view('admin.penugasan-guru.create', compact('gurus', 'mataPelajarans', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        PenugasanGuru::create($request->all());

        return redirect()->route('admin.penugasan-guru.index')->with('success', 'Penugasan Guru berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penugasan = PenugasanGuru::findOrFail($id);
        $gurus = User::query()->where('role', 'guru')->get();
        $mataPelajarans = MataPelajaran::all();
        $kelas = Kelas::all();
        return view('admin.penugasan-guru.edit', compact('penugasan', 'gurus', 'mataPelajarans', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penugasan = PenugasanGuru::findOrFail($id);

        $request->validate([
            'guru_id' => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $penugasan->update($request->all());

        return redirect()->route('admin.penugasan-guru.index')->with('success', 'Penugasan Guru berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penugasan = PenugasanGuru::findOrFail($id);
        $penugasan->delete();

        return redirect()->route('admin.penugasan-guru.index')->with('success', 'Penugasan Guru berhasil dihapus');
    }
}

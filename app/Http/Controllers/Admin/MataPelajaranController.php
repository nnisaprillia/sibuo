<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mataPelajarans = MataPelajaran::query()->paginate(10);
        return view('admin.mata-pelajaran.index', compact('mataPelajarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mata-pelajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:mata_pelajaran',
            'deskripsi' => 'nullable|string',
        ]);

        MataPelajaran::create($request->all());

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil ditambahkan');
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
        $mataPelajaran = MataPelajaran::findOrFail($id);
        return view('admin.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:mata_pelajaran,nama,' . $mataPelajaran->id,
            'deskripsi' => 'nullable|string',
        ]);

        $mataPelajaran->update($request->all());

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil dihapus');
    }
}
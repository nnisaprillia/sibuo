<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = User::query()->where('role', 'siswa')->with(['kelas', 'jurusan'])->paginate(10);
        return view('admin.siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        $jurusans = Jurusan::all();
        return view('admin.siswa.create', compact('kelas', 'jurusans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'nullable|exists:jurusan,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'siswa',
            'kelas_id' => $request->kelas_id,
            'jurusan_id' => $request->jurusan_id,
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan');
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
        $siswa = User::findOrFail($id);
        if ($siswa->role !== 'siswa') abort(404);
        $kelas = Kelas::all();
        $jurusans = Jurusan::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas', 'jurusans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = User::findOrFail($id);
        if ($siswa->role !== 'siswa') abort(404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $siswa->id,
            'password' => 'nullable|string|min:8|confirmed',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'nullable|exists:jurusan,id',
        ]);

        $siswa->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $siswa->password,
            'kelas_id' => $request->kelas_id,
            'jurusan_id' => $request->jurusan_id,
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = User::findOrFail($id);
        if ($siswa->role !== 'siswa') abort(404);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus');
    }
}
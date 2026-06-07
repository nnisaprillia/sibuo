@extends('layouts.app', ['active' => 'kelas', 'title' => 'Edit Kelas'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('admin.kelas.index') }}" class="hover:text-emerald-500 transition-colors">Daftar Kelas</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-bold">{{ $kelas->nama_kelas }}</span>
    </div>
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-medium text-gray-900">Edit Data Kelas</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">Perbarui informasi rombongan belajar</p>
            </div>
            
            <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}" class="p-5 space-y-4">
                @csrf
                @method('PATCH')

                <div class="space-y-1">
                    <label for="nama_kelas" class="block text-xs font-medium text-gray-500">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required
                        class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                        placeholder="Contoh: XII IPA 1">
                    @error('nama_kelas')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="tingkat" class="block text-xs font-medium text-gray-500">Tingkat Pendidikan</label>
                    <select name="tingkat" id="tingkat" required
                        class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors">
                        <option value="SD" {{ old('tingkat', $kelas->tingkat) === 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                        <option value="SMP" {{ old('tingkat', $kelas->tingkat) === 'SMP' ? 'selected' : '' }}>SMP (Menengah Pertama)</option>
                        <option value="SMA" {{ old('tingkat', $kelas->tingkat) === 'SMA' ? 'selected' : '' }}>SMA/SMK (Menengah Atas)</option>
                    </select>
                    @error('tingkat')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                        Perbarui Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

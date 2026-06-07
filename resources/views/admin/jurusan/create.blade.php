@extends('layouts.app', ['active' => 'jurusan', 'title' => 'Tambah Jurusan'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('admin.jurusan.index') }}" class="hover:text-blue-500 transition-colors">Daftar Jurusan</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Tambah Baru</span>
    </div>
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-medium text-gray-900">Data Jurusan</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">Definisikan program keahlian atau jurusan baru</p>
            </div>
            
            <form method="POST" action="{{ route('admin.jurusan.store') }}" class="p-5 space-y-4">
                @csrf

                <div class="space-y-1">
                    <label for="nama_jurusan" class="block text-xs font-medium text-gray-500">Nama Jurusan</label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" value="{{ old('nama_jurusan') }}" required
                        class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition-colors"
                        placeholder="Contoh: Teknik Komputer dan Jaringan">
                    @error('nama_jurusan')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('admin.jurusan.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-[#0f2744] text-white text-xs font-medium rounded-lg hover:bg-[#1a3a5c] transition-colors shadow-lg shadow-blue-900/10">
                        Simpan Jurusan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

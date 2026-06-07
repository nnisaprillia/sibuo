@extends('layouts.app', ['active' => 'mata-pelajaran', 'title' => 'Edit Mata Pelajaran'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('admin.mata-pelajaran.index') }}" class="hover:text-emerald-500 transition-colors">Mata Pelajaran</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-bold">{{ $mataPelajaran->nama }}</span>
    </div>
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-medium text-gray-900">Edit Data Mata Pelajaran</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">Perbarui informasi mata pelajaran yang sudah ada</p>
            </div>
            
            <form method="POST" action="{{ route('admin.mata-pelajaran.update', $mataPelajaran) }}" class="p-5 space-y-4">
                @csrf
                @method('PATCH')

                <div class="space-y-1">
                    <label for="nama" class="block text-xs font-medium text-gray-500">Nama Mata Pelajaran</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $mataPelajaran->nama) }}" required
                        class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                        placeholder="Contoh: Matematika Wajib">
                    @error('nama')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="deskripsi" class="block text-xs font-medium text-gray-500">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                        placeholder="Berikan ringkasan singkat mengenai mata pelajaran ini...">{{ old('deskripsi', $mataPelajaran->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

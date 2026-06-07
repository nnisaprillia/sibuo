@extends('layouts.app', ['active' => 'penugasan-guru', 'title' => 'Tambah Penugasan'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('admin.penugasan-guru.index') }}" class="hover:text-emerald-500 transition-colors">Penugasan Guru</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Tambah Baru</span>
    </div>
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-medium text-gray-900">Buat Penugasan Baru</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">Tentukan guru pengampu untuk mata pelajaran dan kelas tertentu</p>
            </div>
            
            <form method="POST" action="{{ route('admin.penugasan-guru.store') }}" class="p-5 space-y-4">
                @csrf

                <div class="space-y-1">
                    <label for="guru_id" class="block text-xs font-medium text-gray-500">Pilih Guru</label>
                    <select name="guru_id" id="guru_id" required
                        class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors">
                        <option value="">Pilih Guru</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }} ({{ $guru->email }})</option>
                        @endforeach
                    </select>
                    @error('guru_id')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label for="mata_pelajaran_id" class="block text-xs font-medium text-gray-500">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" id="mata_pelajaran_id" required
                            class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mataPelajarans as $mp)
                                <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>{{ $mp->nama }}</option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')
                            <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="kelas_id" class="block text-xs font-medium text-gray-500">Kelas</label>
                        <select name="kelas_id" id="kelas_id" required
                            class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('admin.penugasan-guru.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                        Simpan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

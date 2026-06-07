@extends('layouts.app', ['active' => 'bank-soal', 'title' => 'Buat Bank Soal'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('guru.bank-soal.index') }}" class="hover:text-emerald-500 transition-colors">Bank Soal</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Buat Baru</span>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-medium text-gray-900">Informasi Bank Soal</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">Konfigurasikan detail ujian dan jadwal pelaksanaan</p>
            </div>
            
            <form method="POST" action="{{ route('guru.bank-soal.store') }}" class="p-5 space-y-5">
                @csrf

                @if (!empty($warningNoAssignment) && $warningNoAssignment)
                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-[10px] text-yellow-800 leading-relaxed">Anda belum memiliki penugasan mata pelajaran resmi. Semua mata pelajaran saat ini ditampilkan untuk Anda.</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                        <label for="durasi" class="block text-xs font-medium text-gray-500">Durasi (Menit)</label>
                        <input type="number" name="durasi" id="durasi" value="{{ old('durasi', 60) }}" required min="1"
                            class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                            placeholder="60">
                        @error('durasi')
                            <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="nama_bank" class="block text-xs font-medium text-gray-500">Nama Ujian / Bank Soal</label>
                    <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank') }}" required
                        class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                        placeholder="Contoh: Penilaian Harian - Logika Matematika">
                    @error('nama_bank')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    <div class="space-y-1">
                        <label for="jadwal_mulai" class="block text-xs font-medium text-gray-500">Waktu Mulai</label>
                        <input type="datetime-local" name="jadwal_mulai" id="jadwal_mulai" value="{{ old('jadwal_mulai') }}" required
                            class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors">
                        @error('jadwal_mulai')
                            <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="jadwal_selesai" class="block text-xs font-medium text-gray-500">Waktu Selesai</label>
                        <input type="datetime-local" name="jadwal_selesai" id="jadwal_selesai" value="{{ old('jadwal_selesai') }}" required
                            class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors">
                        @error('jadwal_selesai')
                            <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end gap-3 border-t border-gray-100 mt-4">
                    <a href="{{ route('guru.bank-soal.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                        Simpan Bank Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

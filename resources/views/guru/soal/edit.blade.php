@extends('layouts.app', ['active' => 'bank-soal', 'title' => 'Edit Soal'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('guru.bank-soal.show', $bankSoal->id) }}" class="hover:text-emerald-500 transition-colors">Detail Bank Soal</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Edit Soal</span>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-medium text-gray-900">Edit Pertanyaan</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">Perbarui teks pertanyaan atau pilihan jawaban</p>
            </div>
            
            <form method="POST" action="{{ route('guru.soal.update', [$bankSoal->id, $soal->id]) }}" class="p-5 space-y-6">
                @csrf
                @method('PATCH')

                <div class="space-y-1">
                    <label for="pertanyaan" class="block text-xs font-medium text-gray-500">Teks Pertanyaan</label>
                    <textarea name="pertanyaan" id="pertanyaan" rows="5" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                        placeholder="Tuliskan pertanyaan ujian di sini...">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
                    @error('pertanyaan')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach(['a', 'b', 'c', 'd'] as $key)
                        <div class="space-y-1">
                            <label for="pilihan_{{ $key }}" class="block text-xs font-medium text-gray-500">Pilihan {{ strtoupper($key) }}</label>
                            <div class="flex gap-2">
                                <div class="w-9 h-9 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center text-xs font-bold text-gray-400 shrink-0">
                                    {{ strtoupper($key) }}
                                </div>
                                <input type="text" name="pilihan_{{ $key }}" id="pilihan_{{ $key }}" value="{{ old('pilihan_'.$key, $soal->{'pilihan_'.$key}) }}" required
                                    class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                                    placeholder="Masukkan jawaban...">
                            </div>
                            @error('pilihan_'.$key)
                                <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2 pt-4 border-t border-gray-100">
                    <label class="block text-xs font-medium text-gray-500">Tentukan Jawaban Benar</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['a', 'b', 'c', 'd'] as $key)
                            <label class="flex-1 min-w-[80px]">
                                <input type="radio" name="jawaban_benar" value="{{ $key }}" class="hidden peer" required {{ old('jawaban_benar', $soal->jawaban_benar) == $key ? 'checked' : '' }}>
                                <div class="cursor-pointer py-2 text-center text-xs font-bold border border-gray-200 rounded-lg bg-gray-50 text-gray-400 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-gray-100 transition-all">
                                    {{ strtoupper($key) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('jawaban_benar')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('guru.bank-soal.show', $bankSoal->id) }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                        Perbarui Pertanyaan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

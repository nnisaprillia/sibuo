@extends('layouts.app', ['active' => 'bank-soal', 'title' => 'Tambah Soal'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('guru.bank-soal.show', $bankSoal->id) }}" class="hover:text-emerald-500 transition-colors">Detail Bank Soal</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Tambah Soal</span>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-medium text-gray-900">Pertanyaan Baru</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">Konfigurasikan tipe soal dan detail pertanyaan</p>
            </div>
            
            <form method="POST" action="{{ route('guru.soal.store', $bankSoal->id) }}" class="p-5 space-y-6" x-data="{ tipe: 'pg' }">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label for="tipe" class="block text-xs font-medium text-gray-500">Tipe Soal</label>
                        <select name="tipe" id="tipe" x-model="tipe" required
                            class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors">
                            <option value="pg">Pilihan Ganda</option>
                            <option value="tf">Benar / Salah (T/F)</option>
                            <option value="essay">Essay / Uraian</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between mb-1">
                        <label for="pertanyaan" class="block text-xs font-medium text-gray-500">Teks Pertanyaan</label>
                        <div class="flex gap-1">
                            <button type="button" @click="wrapText('b')" class="p-1 px-2 border border-gray-200 rounded bg-white hover:bg-gray-50 text-[10px] font-bold">B</button>
                            <button type="button" @click="wrapText('i')" class="p-1 px-2 border border-gray-200 rounded bg-white hover:bg-gray-50 text-[10px] italic">I</button>
                            <button type="button" @click="wrapText('u')" class="p-1 px-2 border border-gray-200 rounded bg-white hover:bg-gray-50 text-[10px] underline">U</button>
                        </div>
                    </div>
                    <textarea name="pertanyaan" id="pertanyaan" rows="5" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                        placeholder="Tuliskan pertanyaan ujian di sini...">{{ old('pertanyaan') }}</textarea>
                    @error('pertanyaan')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Multiple Choice (PG) Options -->
                <div x-show="tipe === 'pg'" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['a', 'b', 'c', 'd', 'e'] as $key)
                            <div class="space-y-1">
                                <label for="pilihan_{{ $key }}" class="block text-xs font-medium text-gray-500">Pilihan {{ strtoupper($key) }}</label>
                                <div class="flex gap-2">
                                    <div class="w-9 h-9 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center text-xs font-bold text-gray-400 shrink-0">
                                        {{ strtoupper($key) }}
                                    </div>
                                    <input type="text" name="pilihan_{{ $key }}" id="pilihan_{{ $key }}" value="{{ old('pilihan_'.$key) }}" 
                                        :required="tipe === 'pg' && '{{ $key }}' !== 'e'"
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
                            @foreach(['a', 'b', 'c', 'd', 'e'] as $key)
                                <label class="flex-1 min-w-[80px]">
                                    <input type="radio" name="jawaban_benar_pg" value="{{ $key }}" class="hidden peer" :required="tipe === 'pg'" 
                                        {{ old('jawaban_benar') == $key ? 'checked' : '' }} @change="updateJawaban('{{ $key }}')">
                                    <div class="cursor-pointer py-2 text-center text-xs font-bold border border-gray-200 rounded-lg bg-gray-50 text-gray-400 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-gray-100 transition-all">
                                        {{ strtoupper($key) }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- True / False Options -->
                <div x-show="tipe === 'tf'" class="space-y-4">
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
                        <p class="text-[11px] text-blue-800 leading-relaxed">
                            <strong class="block mb-1">Mode Benar / Salah:</strong>
                            Siswa akan langsung diberikan pilihan <strong>BENAR</strong> atau <strong>SALAH</strong>. Anda hanya perlu menentukan mana jawaban yang tepat di bawah ini.
                        </p>
                    </div>
                    
                    <div class="space-y-2 pt-4">
                        <label class="block text-xs font-medium text-gray-500">Tentukan Jawaban Benar</label>
                        <div class="flex gap-3">
                            <label class="flex-1">
                                <input type="radio" name="jawaban_benar_tf" value="a" class="hidden peer" :required="tipe === 'tf'" @change="updateJawaban('a')">
                                <div class="cursor-pointer py-3 text-center text-xs font-bold border border-gray-200 rounded-xl bg-gray-50 text-gray-400 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-gray-100 transition-all">
                                    BENAR
                                </div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="jawaban_benar_tf" value="b" class="hidden peer" :required="tipe === 'tf'" @change="updateJawaban('b')">
                                <div class="cursor-pointer py-3 text-center text-xs font-bold border border-gray-200 rounded-xl bg-gray-50 text-gray-400 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 hover:bg-gray-100 transition-all">
                                    SALAH
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Essay Info -->
                <div x-show="tipe === 'essay'" class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
                    <p class="text-[11px] text-emerald-800 leading-relaxed">
                        <strong class="block mb-1">Catatan Tipe Essay:</strong>
                        Untuk tipe soal Essay, pilihan jawaban dikosongkan. Siswa akan diberikan area teks untuk menulis jawaban mereka secara naratif.
                    </p>
                    <input type="hidden" name="jawaban_benar_essay" value="essay" x-ref="essayAnswer">
                </div>

                <input type="hidden" name="jawaban_benar" id="jawaban_benar_hidden" value="{{ old('jawaban_benar', 'a') }}">

                <div class="pt-6 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('guru.bank-soal.show', $bankSoal->id) }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                        Simpan Pertanyaan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function wrapText(tag) {
    const textarea = document.getElementById('pertanyaan');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const selectedText = text.substring(start, end);
    const before = text.substring(0, start);
    const after = text.substring(end);
    
    textarea.value = `${before}<${tag}>${selectedText}</${tag}>${after}`;
    textarea.focus();
}

function updateJawaban(val) {
    document.getElementById('jawaban_benar_hidden').value = val;
}
</script>
@endpush

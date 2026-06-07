@extends('layouts.app', ['active' => 'hasil-ujian', 'title' => 'Detail Jawaban Siswa'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('guru.hasil-ujian.index') }}" class="hover:text-emerald-500 transition-colors">Hasil Ujian</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Detail Jawaban</span>
    </div>
@endsection

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl font-bold shrink-0">
                    {{ substr($ujian->siswa->name ?? 'S', 0, 1) }}
                </div>
                <div>
                    <h1 class="text-xl font-medium text-gray-900">{{ $ujian->siswa->name }}</h1>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $ujian->bankSoal->nama_bank }} — {{ $ujian->bankSoal->mataPelajaran->nama }}
                    </p>
                    <div class="mt-2 flex items-center gap-3">
                        <x-badge type="info">Kelas {{ optional($ujian->siswa->kelas)->nama_kelas ?? '-' }}</x-badge>
                        @if($ujian->pelanggaran > 0)
                            <x-badge type="danger">⚠️ {{ $ujian->pelanggaran }} Pelanggaran</x-badge>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center md:items-end bg-gray-50 md:bg-transparent p-4 md:p-0 rounded-xl w-full md:w-auto">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Nilai Akhir</p>
                <div class="text-5xl font-bold {{ $ujian->hasilUjian->nilai >= 75 ? 'text-green-600' : ($ujian->hasilUjian->nilai >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ round($ujian->hasilUjian->nilai) }}
                </div>
                <p class="text-[10px] text-gray-500 mt-2">
                    {{ $ujian->waktu_selesai?->format('d M Y, H:i') }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $questions->where('is_benar', true)->count() }}</p>
                <p class="text-[10px] text-gray-400 uppercase font-bold">Jawaban Benar</p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $questions->where('is_benar', false)->count() }}</p>
                <p class="text-[10px] text-gray-400 uppercase font-bold">Jawaban Salah</p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">
                    @if($ujian->hasilUjian->waktu_penyelesaian)
                        {{ floor($ujian->hasilUjian->waktu_penyelesaian / 60) }}m
                    @else
                        -
                    @endif
                </p>
                <p class="text-[10px] text-gray-400 uppercase font-bold">Durasi Pengerjaan</p>
            </div>
        </div>
    </div>

    <h3 class="text-base font-medium text-gray-900 mb-4">Review Per Soal</h3>
    <div class="space-y-4">
        @foreach ($questions as $index => $qa)
            @php
                $soal = $qa->soal;
                $jawaban = $qa->jawaban_siswa;
                $isCorrect = $qa->is_benar;
            @endphp
            <div class="bg-white border {{ $isCorrect ? 'border-gray-200' : 'border-red-100 bg-red-50/10' }} rounded-xl overflow-hidden shadow-sm">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 {{ $isCorrect ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-lg flex items-center justify-center text-xs font-bold shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-bold {{ $isCorrect ? 'text-green-700' : 'text-red-700' }}">
                                {{ $isCorrect ? 'BENAR' : 'SALAH' }}
                            </span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-800 leading-relaxed mb-6 font-medium">{{ $soal->pertanyaan }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @php
                            $options = ['a' => $soal->pilihan_a, 'b' => $soal->pilihan_b, 'c' => $soal->pilihan_c, 'd' => $soal->pilihan_d];
                        @endphp
                        @foreach ($options as $key => $option)
                            <div class="flex items-start gap-3 p-3 rounded-lg border 
                                {{ $key === $soal->jawaban_benar ? 'bg-green-50 border-green-200' : 'bg-white border-gray-100' }}
                                {{ $key === $jawaban && !$isCorrect ? 'bg-red-50 border-red-200' : '' }}">
                                
                                <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 
                                    {{ $key === $soal->jawaban_benar ? 'bg-green-500 text-white' : ($key === $jawaban ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-400') }}">
                                    {{ strtoupper($key) }}
                                </span>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs {{ $key === $soal->jawaban_benar ? 'text-green-900 font-bold' : ($key === $jawaban ? 'text-red-900 font-bold' : 'text-gray-600') }}">
                                        {{ $option }}
                                    </p>
                                    <div class="mt-1 flex items-center gap-1.5">
                                        @if($key === $soal->jawaban_benar)
                                            <span class="text-[8px] font-bold text-green-600 uppercase">Kunci Jawaban</span>
                                        @endif
                                        @if($key === $jawaban)
                                            <span class="text-[8px] font-bold {{ $isCorrect ? 'text-green-600' : 'text-red-600' }} uppercase">Pilihan Siswa</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

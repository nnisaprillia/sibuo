<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Hasil Ujian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ujian->siswa->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Bank Soal: {{ $ujian->bankSoal->nama_bank }}</p>
                            <p class="text-gray-600 dark:text-gray-400">Mata Pelajaran: {{ $ujian->bankSoal->mataPelajaran->nama }}</p>
                        </div>
                        <a href="{{ route('guru.hasil-ujian.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            ← Kembali
                        </a>
                    </div>

                    <!-- Score Card -->
                    @if ($ujian->hasilUjian)
                        <div class="mb-6 p-6 rounded-lg {{ $ujian->hasilUjian->nilai >= 70 ? 'bg-green-50 dark:bg-green-900' : 'bg-red-50 dark:bg-red-900' }}">
                            <div class="text-center">
                                <div class="text-5xl font-bold {{ $ujian->hasilUjian->nilai >= 70 ? 'text-green-600 dark:text-green-300' : 'text-red-600 dark:text-red-300' }}">
                                    {{ $ujian->hasilUjian->nilai }}
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mt-2">Nilai Ujian</p>
                                @if ($ujian->hasilUjian->waktu_penyelesaian)
                                    <p class="text-gray-600 dark:text-gray-300">Waktu: {{ $ujian->hasilUjian->waktu_penyelesaian }} menit</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Questions and Answers -->
            <div class="space-y-4">
                @foreach ($questions as $qa)
                    @php
                        $soal = $qa->soal;
                        $jawaban = $qa->jawaban_siswa;
                        $isCorrect = $qa->is_benar;
                    @endphp
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    Soal {{ $loop->iteration }}
                                </h3>
                                <span class="px-3 py-1 rounded-full text-sm {{ $isCorrect ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100' }}">
                                    {{ $isCorrect ? '✓ Benar' : '✗ Salah' }}
                                </span>
                            </div>

                            <p class="text-gray-700 dark:text-gray-300 mb-4 font-medium">{{ $soal->pertanyaan }}</p>

                            <div class="space-y-2 mb-4">
                                @php
                                    $options = [
                                        'a' => $soal->pilihan_a,
                                        'b' => $soal->pilihan_b,
                                        'c' => $soal->pilihan_c,
                                        'd' => $soal->pilihan_d,
                                    ];
                                @endphp
                                @foreach ($options as $key => $option)
                                    <div class="p-3 rounded border-2 {{ $key === $soal->jawaban_benar ? 'border-green-500 bg-green-50 dark:bg-green-900' : '' }} {{ $key === $jawaban && !$isCorrect ? 'border-red-500 bg-red-50 dark:bg-red-900' : '' }}">
                                        <p class="text-gray-700 dark:text-gray-300">
                                            <strong>{{ strtoupper($key) }}.</strong> {{ $option }}
                                            @if ($key === $soal->jawaban_benar)
                                                <span class="ml-2 text-green-600 dark:text-green-400">✓ Jawaban Benar</span>
                                            @endif
                                            @if ($key === $jawaban && !$isCorrect)
                                                <span class="ml-2 text-red-600 dark:text-red-400">✗ Jawaban Siswa</span>
                                            @elseif ($key === $jawaban && $isCorrect)
                                                <span class="ml-2 text-green-600 dark:text-green-400">✓ Jawaban Siswa</span>
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

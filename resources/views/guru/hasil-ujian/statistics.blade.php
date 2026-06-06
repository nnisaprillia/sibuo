<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Statistik Soal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Statistik Soal</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $bankSoal->nama_bank }} - {{ $bankSoal->mataPelajaran->nama }}</p>
                        </div>
                        <a href="{{ route('guru.hasil-by-bank-soal', $bankSoal->id) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            ← Kembali
                        </a>
                    </div>

                    @if (empty($questionStats))
                        <p class="text-gray-600 dark:text-gray-400">Belum ada data statistik soal.</p>
                    @else
                        <div class="grid gap-6 lg:grid-cols-3">
                            <div class="lg:col-span-2 space-y-4">
                                @foreach ($questionStats as $stat)
                                    @php
                                        $percentage = $stat['percentage'];
                                        $colorClass = $percentage >= 70 ? 'bg-green-500' : ($percentage >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                                    @endphp
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-900">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                                                    Soal: {{ substr($stat['soal']->pertanyaan, 0, 100) }}{{ strlen($stat['soal']->pertanyaan) > 100 ? '...' : '' }}
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                    Jawaban Benar: <strong>{{ strtoupper($stat['soal']->jawaban_benar) }}</strong>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stat['percentage'] }}%</div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $stat['correct_answers'] }}/{{ $stat['total_answers'] }} benar</p>
                                            </div>
                                        </div>

                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $colorClass }}" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="space-y-4">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-900">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Siswa yang sudah menyelesaikan</h3>
                                    @if(isset($completedUjians) && $completedUjians->isNotEmpty())
                                        <ul class="space-y-2 text-sm">
                                            @foreach($completedUjians as $uj)
                                                <li class="flex justify-between items-center">
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">{{ $uj->siswa->name }}</div>
                                                        <div class="text-xs text-gray-600 dark:text-gray-400">Mulai: {{ $uj->waktu_mulai->format('d M Y H:i') }}</div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">Nilai: {{ optional($uj->hasilUjian)->nilai ?? '-' }}</div>
                                                        <a href="{{ route('guru.hasil-ujian.show', $uj->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 text-sm">Lihat detail</a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-600 dark:text-gray-400">Belum ada siswa yang menyelesaikan.</p>
                                    @endif
                                </div>

                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-900">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Siswa yang belum menyelesaikan</h3>
                                    @if(isset($pendingStudents) && $pendingStudents->isNotEmpty())
                                        <ul class="space-y-2 text-sm">
                                            @foreach($pendingStudents as $siswa)
                                                <li class="flex justify-between items-center">
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">{{ $siswa->name }}</div>
                                                        <div class="text-xs text-gray-600 dark:text-gray-400">Kelas: {{ optional($siswa->kelas)->nama_kelas ?? '-' }}</div>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-sm text-red-600 dark:text-red-400">Belum selesai</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-600 dark:text-gray-400">Semua siswa telah menyelesaikan.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

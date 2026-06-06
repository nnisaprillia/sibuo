<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Guru Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow px-6 py-8">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Bank Soal</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalBankSoal }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow px-6 py-8">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Soal</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalSoal }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow px-6 py-8">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Ujian</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalExams }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Mata Pelajaran & Penugasan -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Mata Pelajaran Ditugaskan</h2>
                        </div>
                        @if ($penugasan->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400">Belum ada penugasan mata pelajaran.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($penugasan as $p)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $p->mataPelajaran->nama }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Kelas: {{ $p->kelas->nama_kelas }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Bank Soal Terbaru -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Bank Soal Terbaru</h2>
                            <a href="{{ route('guru.bank-soal.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                + Buat Bank Soal
                            </a>
                        </div>
                        @if ($bankSoals->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400">Belum ada bank soal.</p>
                        @else
                            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Bank Soal</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode Ujian</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durasi (Menit)</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($bankSoals as $bank)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $bank->nama_bank }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $bank->mataPelajaran->nama }}</td>
                                                <td class="px-4 py-3 text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ $bank->kode_ujian }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $bank->durasi }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="{{ route('guru.bank-soal.show', $bank->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Kelola</a>
                                                    <a href="{{ route('guru.hasil-by-bank-soal', $bank->id) }}" class="ml-3 text-green-600 hover:text-green-900 dark:text-green-400">Hasil</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ujian Mendatang -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Ujian Mendatang</h2>
                    @if ($upcomingExams->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada ujian mendatang.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($upcomingExams as $exam)
                                <div class="p-4 border border-blue-200 dark:border-blue-800 rounded-lg bg-blue-50 dark:bg-blue-900">
                                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $exam->nama_bank }}</h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                                        <span class="block">{{ $exam->mataPelajaran->nama }}</span>
                                        <span class="block mt-1">📅 {{ $exam->jadwal_mulai->format('d M Y') }}</span>
                                        <span class="block">⏱️ {{ $exam->jadwal_mulai->format('H:i') }} - {{ $exam->jadwal_selesai->format('H:i') }}</span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hasil Ujian per Bank Soal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Bank Soal</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $bankSoal->nama_bank }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Ujian</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalExams }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Ujian Selesai</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $completedExams }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Rata-rata Nilai</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ round($averageScore ?? 0, 2) }}</div>
                </div>
            </div>

            <!-- Results Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hasil Ujian</h1>
                        <a href="{{ route('guru.bank-soal.statistik', $bankSoal->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Lihat Statistik
                        </a>
                    </div>

                    @if ($ujians->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Belum ada hasil ujian untuk bank soal ini.</p>
                    @else
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Siswa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nilai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu Penyelesaian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($ujians as $ujian)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $ujian->siswa->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @if ($ujian->hasilUjian)
                                                    <span class="px-3 py-1 rounded-full {{ $ujian->hasilUjian->nilai >= 70 ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100' }}">
                                                        {{ $ujian->hasilUjian->nilai }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400">Belum ada nilai</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="px-3 py-1 rounded-full {{ $ujian->status === 'selesai' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-100' }}">
                                                    {{ ucfirst($ujian->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                @if ($ujian->hasilUjian && $ujian->hasilUjian->waktu_penyelesaian)
                                                    {{ $ujian->hasilUjian->waktu_penyelesaian }} menit
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $ujian->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <a href="{{ route('guru.hasil-ujian.show', $ujian->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                                    Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $ujians->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

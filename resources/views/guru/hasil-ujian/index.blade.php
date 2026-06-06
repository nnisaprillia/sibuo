<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hasil Ujian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Hasil Ujian Siswa</h1>

                    @if ($hasilUjians->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Belum ada hasil ujian.</p>
                    @else
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Siswa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bank Soal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu Penyelesaian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Ujian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($hasilUjians as $hasil)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $hasil->ujian->siswa->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $hasil->ujian->bankSoal->nama_bank }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                @if($hasil->waktu_penyelesaian)
                                                    @php
                                                        $totalSeconds = abs($hasil->waktu_penyelesaian);
                                                        $minutes = floor($totalSeconds / 60);
                                                        $seconds = $totalSeconds % 60;
                                                    @endphp
                                                    {{ $minutes }} menit {{ $seconds }} detik
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $hasil->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <a href="{{ route('guru.hasil-ujian.show', $hasil->ujian->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
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
                            {{ $hasilUjians->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

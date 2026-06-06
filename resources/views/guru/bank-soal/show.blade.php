<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Bank Soal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bankSoal->nama_bank }}</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Mata Pelajaran: {{ $bankSoal->mataPelajaran->nama }}</p>
                            <p class="text-gray-600 dark:text-gray-400">Durasi: {{ $bankSoal->durasi }} menit | Jumlah Soal: {{ $soals->total() }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('guru.bank-soal.edit', $bankSoal->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                                Edit
                            </a>
                            <a href="{{ route('guru.bank-soal.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                Kembali
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded text-green-700 dark:text-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Add Question Button -->
                    <div class="mb-6">
                        <a href="{{ route('guru.soal.create', $bankSoal->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            + Tambah Soal
                        </a>
                        <a href="{{ route('guru.bank-soal.statistik', $bankSoal->id) }}" class="ml-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Lihat Statistik
                        </a>
                    </div>

                    @if ($soals->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Belum ada soal. Silakan tambahkan soal.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($soals as $soal)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                                                Soal {{ $loop->iteration }}
                                            </h3>
                                            <p class="text-gray-700 dark:text-gray-300 mb-3">{{ $soal->pertanyaan }}</p>
                                            <div class="space-y-2 text-sm">
                                                <p class="text-gray-600 dark:text-gray-400"><strong>A.</strong> {{ $soal->pilihan_a }}</p>
                                                <p class="text-gray-600 dark:text-gray-400"><strong>B.</strong> {{ $soal->pilihan_b }}</p>
                                                <p class="text-gray-600 dark:text-gray-400"><strong>C.</strong> {{ $soal->pilihan_c }}</p>
                                                <p class="text-gray-600 dark:text-gray-400"><strong>D.</strong> {{ $soal->pilihan_d }}</p>
                                            </div>
                                            <p class="mt-3 text-sm bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200 p-2 rounded inline-block">
                                                <strong>Jawaban Benar: {{ strtoupper($soal->jawaban_benar) }}</strong>
                                            </p>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <a href="{{ route('guru.soal.edit', [$bankSoal->id, $soal->id]) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400">
                                                Edit
                                            </a>
                                            <form action="{{ route('guru.soal.destroy', [$bankSoal->id, $soal->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $soals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

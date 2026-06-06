<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Ujian Siswa') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main: Available exams as cards -->
                <div class="md:col-span-2 space-y-4">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Ujian Tersedia</h1>

                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded text-green-700 dark:text-green-200">{{ session('success') }}</div>
                        @endif
                        @if (session('info'))
                            <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded text-blue-700 dark:text-blue-200">{{ session('info') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded text-red-700 dark:text-red-200">{{ session('error') }}</div>
                        @endif

                        @if ($availableBankSoal->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400">Belum ada ujian aktif untuk saat ini.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($availableBankSoal as $bank)
                                    @php $activeExam = $examMap->get($bank->id); @endphp
                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4 shadow-sm">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $bank->nama_bank }}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $bank->mataPelajaran->nama }} • {{ $bank->guru->name }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Durasi: {{ $bank->durasi }} menit</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $bank->jadwal_mulai->format('d M Y H:i') }} — {{ $bank->jadwal_selesai->format('d M Y H:i') }}</p>
                                            </div>
                                            <div class="text-right">
                                                @if ($activeExam)
                                                    @if ($activeExam->status === 'ongoing')
                                                        <span class="inline-flex items-center rounded-full bg-yellow-100 text-yellow-800 px-3 py-1 text-xs font-semibold">Sedang</span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-xs font-semibold">Selesai</span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-blue-100 text-blue-800 px-3 py-1 text-xs font-semibold">Siap</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-4 flex gap-2">
                                            @if ($activeExam)
                                                <a href="{{ route('siswa.ujian.show', $activeExam) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Lihat</a>
                                            @else
                                                <form action="{{ route('siswa.ujian.store') }}" method="POST" class="w-full">
                                                    @csrf
                                                    <input type="hidden" name="bank_soal_id" value="{{ $bank->id }}">
                                                    <div class="flex flex-col gap-2">
                                                        <input type="text" name="kode_ujian" placeholder="Kode: XXX-XXX" class="text-sm rounded border-gray-300 dark:bg-gray-800 dark:text-white uppercase px-2 py-1" required>
                                                        <button type="submit" class="w-full bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-sm font-semibold">Mulai Ujian</button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Old history moved to right panel; keep space for future content -->
                </div>

                <!-- Right panel: Upcoming and History -->
                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Ujian Hari Ini & Besok</h2>
                        @if (isset($upcomingBankSoal) && $upcomingBankSoal->isNotEmpty())
                            <ul class="space-y-3">
                                @foreach ($upcomingBankSoal as $bank)
                                    <li class="p-3 rounded border border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $bank->nama_bank }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $bank->mataPelajaran->nama }}</div>
                                                <div class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mt-1 countdown" data-time="{{ $bank->jadwal_mulai->timestamp }}">
                                                    Memuat countdown...
                                                </div>
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $bank->durasi }}m</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">Tidak ada ujian terjadwal untuk hari ini atau besok.</p>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Ujian</h2>
                            <a href="#" class="text-sm text-gray-500 dark:text-gray-400">Lihat semua</a>
                        </div>

                        @if ($history->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400">Belum ada catatan hasil ujian.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($history->take(6) as $ujian)
                                    <div class="flex justify-between items-center p-3 border border-gray-200 dark:border-gray-700 rounded">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $ujian->bankSoal->nama_bank }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $ujian->bankSoal->mataPelajaran->nama }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($ujian->status) }}</div>
                                            <a href="{{ route('siswa.ujian.show', $ujian) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 text-sm">Lihat</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">Halaman: {{ $history->currentPage() }} / {{ $history->lastPage() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countdowns = document.querySelectorAll('.countdown');
            
            const updateCountdowns = () => {
                const now = Math.floor(Date.now() / 1000);
                
                countdowns.forEach(el => {
                    const target = parseInt(el.getAttribute('data-time'));
                    const diff = target - now;
                    
                    if (diff <= 0) {
                        el.textContent = 'Siap Dimulai (Refresh halaman)';
                        el.classList.remove('text-indigo-600');
                        el.classList.add('text-green-600');
                    } else {
                        const days = Math.floor(diff / 86400);
                        const hours = Math.floor((diff % 86400) / 3600);
                        const mins = Math.floor((diff % 3600) / 60);
                        const secs = diff % 60;
                        
                        let text = 'Mulai dalam: ';
                        if (days > 0) text += days + 'h ';
                        if (hours > 0 || days > 0) text += hours + 'j ';
                        text += mins + 'm ' + secs + 'd';
                        
                        el.textContent = text;
                    }
                });
            };
            
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        });
    </script>
</x-app-layout>

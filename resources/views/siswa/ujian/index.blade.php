@extends('layouts.exam')

@section('content')
    <div class="flex-1 flex items-center justify-center p-6 bg-gray-50">
        <div class="w-full max-w-xl">
            <!-- SIBUO Logo -->
            <div class="flex items-center justify-center gap-2 mb-8">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center overflow-hidden shadow-sm">
                    <img src="{{ asset('assets/img/logoSIBUO.png') }}" alt="SIBUO" class="w-6 h-6 object-contain">
                </div>
                <span class="text-gray-900 text-lg font-medium tracking-tight">SIBUO</span>
            </div>

            @if ($availableBankSoal->isEmpty())
                <!-- Kondisi A: Belum ada ujian aktif -->
                <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-medium text-gray-900">Belum ada ujian hari ini</h2>
                    <p class="text-sm text-gray-500 mt-2 max-w-xs mx-auto">
                        Silakan hubungi pengawas atau guru pengampu jika jadwal ujian Anda seharusnya sudah dimulai.
                    </p>
                    
                    @if (isset($upcomingBankSoal) && $upcomingBankSoal->isNotEmpty())
                        <div class="mt-8 pt-8 border-t border-gray-100 text-left">
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Jadwal Mendatang</h3>
                            <div class="space-y-3">
                                @foreach ($upcomingBankSoal->take(2) as $upcoming)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                        <div>
                                            <p class="text-xs font-medium text-gray-900">{{ $upcoming->nama_bank }}</p>
                                            <p class="text-[10px] text-gray-500">{{ $upcoming->mataPelajaran->nama }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-medium text-emerald-600">Besok, {{ $upcoming->jadwal_mulai->format('H:i') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="mt-10">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">
                            Keluar dari Sesi
                        </button>
                    </form>
                </div>
            @else
                <!-- Kondisi B: Ujian aktif, input kode -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                    @foreach ($availableBankSoal as $bank)
                        @php $activeExam = $examMap->get($bank->id); @endphp
                        
                        <div class="mb-8 last:mb-0">
                            <div class="text-center mb-6">
                                <h2 class="text-xl font-medium text-gray-900">Masukkan Kode Ujian</h2>
                                <p class="text-xs text-gray-500 mt-1">{{ $bank->mataPelajaran->nama }} • Kelas {{ Auth::user()->kelas->nama_kelas ?? '-' }}</p>
                            </div>

                            @if (session('error'))
                                <div class="mb-6 p-3 bg-red-50 border border-red-100 rounded-lg text-[10px] text-red-600 text-center">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($activeExam && $activeExam->status === 'ongoing')
                                <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-xl text-center mb-6">
                                    <p class="text-xs text-yellow-800 font-medium">Sesi ujian Anda sedang berlangsung</p>
                                    <a href="{{ route('siswa.ujian.show', $activeExam) }}" class="mt-3 inline-block px-6 py-2 bg-yellow-600 text-white text-xs font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                                        Lanjutkan Ujian
                                    </a>
                                </div>
                            @elseif ($activeExam && $activeExam->status === 'completed')
                                <div class="p-4 bg-green-50 border border-green-100 rounded-xl text-center mb-6">
                                    <p class="text-xs text-green-800 font-medium">Anda telah menyelesaikan ujian ini</p>
                                    <a href="{{ route('siswa.ujian.show', $activeExam) }}" class="mt-3 inline-block px-6 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        Lihat Hasil
                                    </a>
                                </div>
                            @else
                                <form action="{{ route('siswa.ujian.store') }}" method="POST" class="space-y-6">
                                    @csrf
                                    <input type="hidden" name="bank_soal_id" value="{{ $bank->id }}">
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-center">
                                            <input type="text" name="kode_ujian" maxlength="7" required
                                                class="w-full max-w-[240px] h-14 text-center text-2xl font-mono tracking-[0.5em] border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-all uppercase"
                                                placeholder="XXX-XXX"
                                                x-mask="aaa-aaa">
                                        </div>
                                        <p class="text-[10px] text-center text-gray-400">Kode diperbarui setiap 5 menit oleh pengawas</p>
                                    </div>

                                    <button type="submit" class="w-full h-12 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                                        Mulai Ujian
                                    </button>
                                </form>
                            @endif
                        </div>
                        @if(!$loop->last) <hr class="my-8 border-gray-100"> @endif
                    @endforeach
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-8 text-center">
                    @csrf
                    <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">
                        Keluar dari Sesi
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/@alpinejs/mask@3.x.x/dist/cdn.min.js" defer></script>
@endpush

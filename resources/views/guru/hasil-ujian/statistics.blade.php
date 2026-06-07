@extends('layouts.app', ['active' => 'bank-soal', 'title' => 'Statistik Soal'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('guru.bank-soal.index') }}" class="hover:text-emerald-500 transition-colors">Bank Soal</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route('guru.bank-soal.show', $bankSoal->id) }}" class="hover:text-emerald-500 transition-colors">Detail</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Statistik</span>
    </div>
@endsection

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <h1 class="text-xl font-medium text-gray-900">Analisis Performa Soal</h1>
        <p class="text-xs text-gray-500 mt-1">{{ $bankSoal->nama_bank }} — {{ $bankSoal->mataPelajaran->nama }}</p>
    </div>

    @if (empty($questionStats))
        <div class="bg-white border border-gray-200 rounded-xl">
            <x-empty-state entity="data statistik" />
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Persentase Jawaban Benar</h3>
                @foreach ($questionStats as $index => $stat)
                    @php
                        $percentage = $stat['percentage'];
                        $colorClass = $percentage >= 75 ? 'bg-green-500' : ($percentage >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                        $bgClass = $percentage >= 75 ? 'bg-green-50' : ($percentage >= 50 ? 'bg-yellow-50' : 'bg-red-50');
                    @endphp
                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-6 h-6 bg-gray-100 text-gray-600 rounded flex items-center justify-center text-[10px] font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Soal ID: #{{ $stat['soal']->id }}</span>
                                </div>
                                <p class="text-sm text-gray-800 line-clamp-2">{{ $stat['soal']->pertanyaan }}</p>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $percentage }}%</div>
                                <p class="text-[10px] text-gray-500 font-medium">{{ $stat['correct_answers'] }}/{{ $stat['total_answers'] }} Siswa Benar</p>
                            </div>
                        </div>

                        <div class="relative h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div class="absolute top-0 left-0 h-full {{ $colorClass }} transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-6">
                <!-- Completed Students -->
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-900 mb-4 flex items-center justify-between">
                        Sudah Selesai
                        <x-badge type="success">{{ $completedUjians->count() }}</x-badge>
                    </h3>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                        @forelse($completedUjians as $uj)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition-colors border border-transparent hover:border-gray-100">
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-900 truncate">{{ $uj->siswa->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ optional($uj->siswa->kelas)->nama_kelas }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs font-bold text-emerald-600">{{ optional($uj->hasilUjian)->nilai ?? '-' }}</p>
                                    <a href="{{ route('guru.hasil-ujian.show', $uj->id) }}" class="text-[8px] font-bold text-gray-400 hover:text-emerald-500 uppercase">Detail</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-[10px] text-gray-400 italic text-center py-4">Belum ada siswa selesai</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pending Students -->
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-900 mb-4 flex items-center justify-between">
                        Belum Selesai
                        <x-badge type="warning">{{ $pendingStudents->count() }}</x-badge>
                    </h3>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                        @forelse($pendingStudents as $siswa)
                            <div class="flex items-center justify-between p-2 rounded-lg border border-dashed border-gray-200">
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-600 truncate">{{ $siswa->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ optional($siswa->kelas)->nama_kelas }}</p>
                                </div>
                                <span class="text-[8px] font-bold text-yellow-600 bg-yellow-50 px-1 py-0.5 rounded uppercase">Pending</span>
                            </div>
                        @empty
                            <p class="text-[10px] text-gray-400 italic text-center py-4">Semua siswa sudah selesai</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

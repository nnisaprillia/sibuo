@extends('layouts.app', ['active' => 'bank-soal', 'title' => 'Hasil Ujian per Bank Soal'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('guru.bank-soal.index') }}" class="hover:text-emerald-500 transition-colors">Bank Soal</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Hasil Pengerjaan</span>
    </div>
@endsection

@section('content')
    <div class="bg-primary rounded-2xl p-6 mb-6 shadow-lg shadow-emerald-900/10 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-xl font-medium mb-4">{{ $bankSoal->nama_bank }}</h1>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-white/40 text-[10px] uppercase tracking-widest font-bold mb-1">Total Peserta</p>
                    <p class="text-2xl font-bold">{{ $totalExams }}</p>
                </div>
                <div>
                    <p class="text-white/40 text-[10px] uppercase tracking-widest font-bold mb-1">Sudah Selesai</p>
                    <p class="text-2xl font-bold text-green-400">{{ $completedExams }}</p>
                </div>
                <div>
                    <p class="text-white/40 text-[10px] uppercase tracking-widest font-bold mb-1">Rata-rata Nilai</p>
                    <p class="text-2xl font-bold text-emerald-300">{{ round($averageScore ?? 0, 1) }}</p>
                </div>
                <div class="flex flex-col justify-end">
                    <a href="{{ route('guru.bank-soal.statistik', $bankSoal->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-lg hover:bg-emerald-600 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Lihat Statistik Soal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900">Daftar Pengerjaan Siswa</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/50">
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Siswa</th>
                        <th class="text-center px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Nilai</th>
                        <th class="text-center px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Durasi</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Tanggal</th>
                        <th class="text-right px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ujians as $ujian)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-medium">
                                        {{ substr($ujian->siswa->name ?? 'S', 0, 1) }}
                                    </div>
                                    <span class="text-gray-900 font-medium">{{ $ujian->siswa->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if ($ujian->hasilUjian)
                                    <span class="text-lg font-bold {{ $ujian->hasilUjian->nilai >= 75 ? 'text-green-600' : ($ujian->hasilUjian->nilai >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ round($ujian->hasilUjian->nilai) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($ujian->status === 'completed')
                                    <x-badge type="success">Selesai</x-badge>
                                @else
                                    <x-badge type="warning">Proses</x-badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">
                                @if ($ujian->hasilUjian && $ujian->hasilUjian->waktu_penyelesaian)
                                    {{ floor($ujian->hasilUjian->waktu_penyelesaian / 60) }}m {{ $ujian->hasilUjian->waktu_penyelesaian % 60 }}d
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">
                                {{ $ujian->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('guru.hasil-ujian.show', $ujian->id) }}" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg hover:bg-emerald-100 transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state entity="pengerjaan siswa" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($ujians->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $ujians->links() }}
            </div>
        @endif
    </div>
@endsection

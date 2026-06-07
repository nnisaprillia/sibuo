@extends('layouts.app', ['active' => 'dashboard', 'title' => 'Dashboard Guru'])

@section('content')
    <!-- Statistics Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-stat-card 
            label="Bank Soal Saya" 
            value="{{ $totalBankSoal }}" 
            info="Koleksi bank soal Anda"
            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>'
        />
        <x-stat-card 
            label="Total Soal" 
            value="{{ $totalSoal }}" 
            info="Jumlah soal di semua bank soal"
            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        />
        <x-stat-card 
            label="Ujian Selesai" 
            value="{{ $totalExams }}" 
            info="Total riwayat pengerjaan siswa"
            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        />
    </div>

    <!-- Data Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <!-- Bank Soal List -->
        <div class="lg:col-span-8 bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Bank Soal Aktif</h3>
                <a href="{{ route('guru.bank-soal.create') }}" class="px-3 py-1.5 bg-primary text-white text-[10px] font-medium rounded-lg hover:bg-primary-dark transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Baru
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 text-gray-500 font-medium">Bank Soal</th>
                            <th class="text-left py-2 text-gray-500 font-medium text-center">Soal</th>
                            <th class="text-left py-2 text-gray-500 font-medium text-center">Kode</th>
                            <th class="text-center py-2 text-gray-500 font-medium">Status</th>
                            <th class="text-right py-2 text-gray-500 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bankSoals as $bank)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3">
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 font-medium">{{ $bank->nama_bank }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $bank->mataPelajaran->nama }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center text-gray-600 font-medium">{{ $bank->soal->count() }}</td>
                                <td class="py-3 text-center">
                                    <span class="font-mono text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">{{ $bank->kode_ujian }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    @php
                                        $now = now();
                                        $status = 'Draft';
                                        $type = 'info';
                                        if ($bank->jadwal_mulai <= $now && $bank->jadwal_selesai >= $now) {
                                            $status = 'Aktif';
                                            $type = 'success';
                                        } elseif ($bank->jadwal_mulai > $now) {
                                            $status = 'Terjadwal';
                                            $type = 'warning';
                                        } else {
                                            $status = 'Selesai';
                                            $type = 'danger';
                                        }
                                    @endphp
                                    <x-badge :type="$type">{{ $status }}</x-badge>
                                </td>
                                <td class="py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('guru.bank-soal.show', $bank->id) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Kelola">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                        </a>
                                        <a href="{{ route('guru.hasil-by-bank-soal', $bank->id) }}" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Hasil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <x-empty-state entity="bank soal" :route="route('guru.bank-soal.create')" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upcoming Exams -->
        <div class="lg:col-span-4 bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Ujian Mendatang</h3>
            
            <div class="space-y-3">
                @forelse($upcomingExams as $exam)
                    <div class="p-3 bg-emerald-50/50 border border-emerald-100 rounded-xl">
                        <div class="flex items-start justify-between">
                            <h4 class="text-xs font-medium text-gray-900">{{ $exam->nama_bank }}</h4>
                            @php
                                $diff = now()->diffInHours($exam->jadwal_mulai);
                                $timeText = $diff > 24 ? floor($diff/24) . ' hari' : $diff . ' jam';
                            @endphp
                            <span class="text-[10px] font-medium text-emerald-600">mulai dalam {{ $timeText }}</span>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1">{{ $exam->mataPelajaran->nama }}</p>
                        <div class="mt-3 flex items-center gap-2 text-[10px] text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $exam->jadwal_mulai->format('d M Y, H:i') }}
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <p class="text-[10px] text-gray-400 italic">Tidak ada ujian mendatang</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

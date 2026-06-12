@extends('layouts.app', ['active' => 'dashboard', 'title' => 'Dashboard Admin'])

@section('content')
    <!-- Statistics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card 
            label="Total Guru" 
            value="{{ \App\Models\User::where('role', 'guru')->count() }}" 
            info="{{ \App\Models\User::where('role', 'guru')->whereDate('created_at', now())->count() }} baru hari ini"
            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>'
        />
        <x-stat-card 
            label="Total Siswa" 
            value="{{ \App\Models\User::where('role', 'siswa')->count() }}" 
            info="{{ \App\Models\User::where('role', 'siswa')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }} minggu ini"
            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>'
        />
        <x-stat-card 
            label="Ujian Aktif" 
            value="{{ \App\Models\Ujian::where('status', 'ongoing')->count() }}" 
            info="sedang berlangsung"
            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        />
        <x-stat-card 
            label="Mata Pelajaran" 
            value="{{ \App\Models\MataPelajaran::count() }}" 
            info="{{ \App\Models\Jurusan::count() }} Jurusan"
            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'
        />
    </div>

    <!-- Data Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Latest Assignments -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Penugasan Guru Terbaru</h3>
                <a href="{{ route('admin.penugasan-guru.index') }}" class="text-[10px] text-emerald-500 hover:text-emerald-600 font-medium">Lihat semua →</a>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 text-gray-500 font-medium">Guru</th>
                            <th class="text-left py-2 text-gray-500 font-medium">Mata Pelajaran</th>
                            <th class="text-left py-2 text-gray-500 font-medium">Kelas</th>
                            <th class="text-center py-2 text-gray-500 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\PenugasanGuru::with(['guru', 'mataPelajaran', 'kelas'])->latest()->take(5)->get() as $penugasan)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3 text-gray-900 font-medium">{{ $penugasan->guru->name }}</td>
                                <td class="py-3 text-gray-700">{{ $penugasan->mataPelajaran->nama }}</td>
                                <td class="py-3 text-gray-700">{{ $penugasan->kelas->nama_kelas }}</td>
                                <td class="py-3 text-center">
                                    <x-badge type="success">Aktif</x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8">
                                    <x-empty-state entity="penugasan" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Feed (Simulated) -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Aktivitas Terbaru</h3>
            </div>
            
            <div class="space-y-4">
                @php
                    $activities = [
                        ['dot' => 'bg-emerald-500', 'text' => 'Guru <b>Budi Santoso</b> membuat Bank Soal baru', 'time' => '2 menit yang lalu'],
                        ['dot' => 'bg-green-500', 'text' => 'Siswa <b>Andi Wijaya</b> menyelesaikan ujian Matematika', 'time' => '15 menit yang lalu'],
                        ['dot' => 'bg-yellow-500', 'text' => 'Admin mengubah jadwal ujian Bahasa Inggris', 'time' => '1 jam yang lalu'],
                        ['dot' => 'bg-emerald-500', 'text' => 'Guru <b>Siti Aminah</b> menambahkan 20 soal baru', 'time' => '3 jam yang lalu'],
                        ['dot' => 'bg-red-500', 'text' => 'Pelanggaran terdeteksi pada sesi ujian <b>IPA Terpadu</b>', 'time' => '5 jam yang lalu'],
                    ];
                @endphp

                @foreach($activities as $activity)
                    <div class="flex gap-3">
                        <div class="mt-1.5 w-2 h-2 rounded-full {{ $activity['dot'] }} shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-700 leading-relaxed">{!! $activity['text'] !!}</p>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

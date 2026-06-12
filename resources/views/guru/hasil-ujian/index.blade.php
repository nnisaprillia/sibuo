@extends('layouts.app', ['active' => 'hasil-ujian', 'title' => 'Hasil Ujian'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-medium text-gray-900">Hasil Ujian Siswa</h2>
            <p class="text-xs text-gray-500 mt-1">Pantau performa siswa dan riwayat pengerjaan ujian</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/50">
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Siswa</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Bank Soal / Mapel</th>
                        <th class="text-center px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Nilai</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Durasi</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Waktu Selesai</th>
                        <th class="text-right px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($hasilUjians as $hasil)
                        @php
                            $pelanggaran = optional($hasil->ujian)->pelanggaran ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors {{ $pelanggaran > 0 ? 'bg-yellow-50/30' : '' }}">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-medium">
                                        {{ substr($hasil->ujian->siswa->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 font-medium">{{ $hasil->ujian->siswa->name ?? '-' }}</span>
                                        <span class="text-[10px] text-gray-400">Kelas {{ optional($hasil->ujian->siswa->kelas)->nama_kelas ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-col">
                                    <span class="text-gray-700 font-medium">{{ $hasil->ujian->bankSoal->nama_bank }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $hasil->ujian->bankSoal->mataPelajaran->nama }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-lg font-bold {{ $hasil->nilai >= 75 ? 'text-green-600' : ($hasil->nilai >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ round($hasil->nilai) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">
                                @if($hasil->waktu_penyelesaian)
                                    @php
                                        $min = floor($hasil->waktu_penyelesaian / 60);
                                        $sec = $hasil->waktu_penyelesaian % 60;
                                    @endphp
                                    {{ $min }}m {{ $sec }}d
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">
                                {{ $hasil->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    @if($pelanggaran > 0)
                                        <span class="flex items-center gap-1 text-[10px] font-bold text-yellow-600 bg-yellow-50 px-1.5 py-0.5 rounded border border-yellow-200" title="Pelanggaran Terdeteksi">
                                            ⚠️ {{ $pelanggaran }}
                                        </span>
                                    @endif
                                    <a href="{{ route('guru.hasil-ujian.show', $hasil->ujian->id) }}" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg hover:bg-emerald-100 transition-colors">
                                        Lihat Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state entity="hasil ujian" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($hasilUjians->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $hasilUjians->links() }}
            </div>
        @endif
    </div>
@endsection

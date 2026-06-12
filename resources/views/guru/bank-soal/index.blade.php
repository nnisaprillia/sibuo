@extends('layouts.app', ['active' => 'bank-soal', 'title' => 'Bank Soal'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-medium text-gray-900">Daftar Bank Soal</h2>
            <p class="text-xs text-gray-500 mt-1">Kelola paket pertanyaan ujian dan jadwal pengerjaan</p>
        </div>
        @if($hasAssignments)
            <a href="{{ route('guru.bank-soal.create') }}" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors flex items-center gap-2 shadow-lg shadow-emerald-900/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Bank Soal
            </a>
        @else
            <button disabled title="Belum ada Mata Pelajaran yang ditugaskan" class="px-4 py-2 bg-gray-300 text-gray-500 text-xs font-medium rounded-lg flex items-center gap-2 cursor-not-allowed shadow-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Bank Soal
            </button>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/50">
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Info Ujian</th>
                        <th class="text-center px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Soal</th>
                        <th class="text-center px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Durasi</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Jadwal</th>
                        <th class="text-center px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Status</th>
                        <th class="text-right px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bankSoals as $bank)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-medium">{{ $bank->nama_bank }}</span>
                                    <div class="flex items-center gap-1">
                                        <span class="text-[10px] text-gray-400 uppercase tracking-tight">{{ $bank->mataPelajaran->nama }}</span>
                                        <span class="text-[10px] text-gray-300">•</span>
                                        <span class="text-[10px] text-emerald-500 font-medium">{{ $bank->kelas->nama_kelas ?? 'Semua' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center font-medium text-gray-700">{{ $bank->soal->count() }}</td>
                            <td class="px-5 py-3.5 text-center text-gray-600">{{ $bank->durasi }} Menit</td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-col">
                                    <span class="text-gray-700">{{ $bank->jadwal_mulai->format('d M Y, H:i') }}</span>
                                    <span class="text-[10px] text-gray-400">s/d {{ $bank->jadwal_selesai->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
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
                                <div class="flex flex-col items-center gap-1">
                                    <x-badge :type="$type">{{ $status }}</x-badge>
                                    @if($bank->is_published)
                                        <span class="text-[9px] text-emerald-600 font-medium uppercase tracking-tighter">Published</span>
                                    @else
                                        <span class="text-[9px] text-gray-400 font-medium uppercase tracking-tighter">Draft</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <form action="{{ route('guru.bank-soal.toggle-publish', $bank->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-1.5 {{ $bank->is_published ? 'text-gray-400 hover:text-yellow-600 hover:bg-yellow-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-colors" title="{{ $bank->is_published ? 'Unpublish' : 'Publish' }}">
                                            @if($bank->is_published)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <a href="{{ route('guru.bank-soal.show', $bank->id) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Kelola Soal">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </a>
                                    <a href="{{ route('guru.bank-soal.edit', $bank->id) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.242 19.172l3.536-3.536M11 5l3.536-3.536m0 0l3.536 3.536m-3.536-3.536v13.072"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('guru.bank-soal.destroy', $bank->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state 
                                    entity="bank soal" 
                                    :route="$hasAssignments ? route('guru.bank-soal.create') : null" 
                                />
                                @if(!$hasAssignments)
                                    <div class="flex flex-col items-center -mt-8 pb-8">
                                        <div class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-medium border border-red-100">
                                            Belum ada Mata Pelajaran yang ditugaskan
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bankSoals->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $bankSoals->links() }}
            </div>
        @endif
    </div>
@endsection

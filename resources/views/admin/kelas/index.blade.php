@extends('layouts.app', ['active' => 'kelas', 'title' => 'Manajemen Kelas'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-medium text-gray-900">Daftar Kelas</h2>
            <p class="text-xs text-gray-500 mt-1">Kelola data kelas dan tingkatan pendidikan</p>
        </div>
        <a href="{{ route('admin.kelas.create') }}" class="px-4 py-2 bg-[#0f2744] text-white text-xs font-medium rounded-lg hover:bg-[#1a3a5c] transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kelas
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/50">
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Nama Kelas</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Tingkat</th>
                        <th class="text-right px-5 py-3 text-gray-500 font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kelas as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="text-gray-900 font-medium">{{ $item->nama_kelas }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <x-badge type="primary">{{ $item->tingkat }}</x-badge>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.kelas.edit', $item) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.242 19.172l3.536-3.536M11 5l3.536-3.536m0 0l3.536 3.536m-3.536-3.536v13.072"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.kelas.destroy', $item) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8">
                                <x-empty-state entity="kelas" :route="route('admin.kelas.create')" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($kelas->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $kelas->links() }}
            </div>
        @endif
    </div>
@endsection

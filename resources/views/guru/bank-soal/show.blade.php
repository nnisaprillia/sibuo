@extends('layouts.app', ['active' => 'bank-soal', 'title' => 'Kelola Soal'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('guru.bank-soal.index') }}" class="hover:text-emerald-500 transition-colors">Bank Soal</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900">Detail & Pertanyaan</span>
    </div>
@endsection

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-medium text-gray-900">{{ $bankSoal->nama_bank }}</h1>
                <div class="mt-2 flex flex-wrap gap-x-4 gap-y-2 text-xs text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        {{ $bankSoal->mataPelajaran->nama }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $bankSoal->durasi }} Menit
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        {{ $soals->total() }} Pertanyaan
                    </div>
                </div>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('guru.bank-soal.edit', $bankSoal->id) }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.242 19.172l3.536-3.536M11 5l3.536-3.536m0 0l3.536 3.536m-3.536-3.536v13.072"></path></svg>
                    Edit Info
                </a>
                <a href="{{ route('guru.bank-soal.statistik', $bankSoal->id) }}" class="px-4 py-2 bg-green-50 text-green-700 border border-green-200 text-xs font-medium rounded-lg hover:bg-green-100 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Statistik
                </a>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-medium text-gray-900">Daftar Pertanyaan</h2>
        <a href="{{ route('guru.soal.create', $bankSoal->id) }}" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors flex items-center gap-2 shadow-lg shadow-emerald-900/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Soal
        </a>
    </div>

    @if ($soals->isEmpty())
        <div class="bg-white border border-gray-200 rounded-xl">
            <x-empty-state entity="soal" :route="route('guru.soal.create', $bankSoal->id)" />
        </div>
    @else
        <div class="space-y-4">
            @foreach ($soals as $index => $soal)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:border-emerald-300 transition-colors">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 bg-gray-100 text-gray-500 rounded-lg flex items-center justify-center text-xs font-bold">
                                    {{ $soals->firstItem() + $index }}
                                </span>
                                @php
                                    $tipeLabels = ['pg' => 'Pilihan Ganda', 'tf' => 'Benar / Salah', 'essay' => 'Essay'];
                                    $tipeColors = ['pg' => 'primary', 'tf' => 'info', 'essay' => 'success'];
                                @endphp
                                <x-badge :type="$tipeColors[$soal->tipe]">{{ $tipeLabels[$soal->tipe] }}</x-badge>
                                <x-badge type="secondary">ID: #{{ $soal->id }}</x-badge>
                            </div>
                            <div class="flex gap-1">
                                <a href="{{ route('guru.soal.edit', [$bankSoal->id, $soal->id]) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit Soal">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.242 19.172l3.536-3.536M11 5l3.536-3.536m0 0l3.536 3.536m-3.536-3.536v13.072"></path></svg>
                                </a>
                                <form action="{{ route('guru.soal.destroy', [$bankSoal->id, $soal->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Soal">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="prose prose-sm max-w-none text-sm text-gray-800 leading-relaxed mb-6">
                            {!! nl2br($soal->pertanyaan) !!}
                        </div>

                        @if($soal->tipe === 'pg')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                                @foreach(['a', 'b', 'c', 'd', 'e'] as $key)
                                    @if($soal->{'pilihan_'.$key})
                                        <div class="flex items-start gap-3 p-3 rounded-lg border {{ $soal->jawaban_benar == $key ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-100' }}">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 {{ $soal->jawaban_benar == $key ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                                {{ strtoupper($key) }}
                                            </span>
                                            <span class="text-xs {{ $soal->jawaban_benar == $key ? 'text-green-900 font-medium' : 'text-gray-600' }}">
                                                {{ $soal->{'pilihan_'.$key} }}
                                            </span>
                                            @if($soal->jawaban_benar == $key)
                                                <svg class="w-4 h-4 text-green-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif($soal->tipe === 'tf')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                                @foreach(['a' => 'Benar', 'b' => 'Salah'] as $key => $label)
                                    <div class="flex items-start gap-3 p-3 rounded-lg border {{ $soal->jawaban_benar == $key ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-100' }}">
                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 {{ $soal->jawaban_benar == $key ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                            {{ $key === 'a' ? 'T' : 'F' }}
                                        </span>
                                        <span class="text-xs {{ $soal->jawaban_benar == $key ? 'text-green-900 font-medium' : 'text-gray-600' }}">
                                            {{ $label }}
                                        </span>
                                        @if($soal->jawaban_benar == $key)
                                            <svg class="w-4 h-4 text-green-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif($soal->tipe === 'essay')
                            <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl mb-6">
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-2">Siswa akan menjawab pada kolom teks</p>
                                <div class="h-20 border-2 border-dashed border-gray-200 rounded-lg"></div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($soals->hasPages())
            <div class="mt-6">
                {{ $soals->links() }}
            </div>
        @endif
    @endif
@endsection

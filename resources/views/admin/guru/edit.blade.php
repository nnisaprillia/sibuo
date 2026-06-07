@extends('layouts.app', ['active' => 'guru', 'title' => 'Edit Guru'])

@section('breadcrumb')
    <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
        <a href="{{ route('admin.guru.index') }}" class="hover:text-emerald-500 transition-colors">Daftar Guru</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-bold">{{ $guru->name }}</span>
    </div>
@endsection

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-medium text-gray-900">Edit Akun Guru</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">Perbarui informasi profil atau ubah password guru</p>
            </div>
            
            <form method="POST" action="{{ route('admin.guru.update', $guru) }}" class="p-5 space-y-4">
                @csrf
                @method('PATCH')

                <div class="space-y-1">
                    <label for="name" class="block text-xs font-medium text-gray-500">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $guru->name) }}" required
                        class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                        placeholder="Contoh: Budi Santoso, S.Pd.">
                    @error('name')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="email" class="block text-xs font-medium text-gray-500">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $guru->email) }}" required
                        class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                        placeholder="guru@sekolah.sch.id">
                    @error('email')
                        <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 border-t border-gray-100 mt-6">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-3">Ubah Password (Opsional)</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-medium text-gray-500">Password Baru</label>
                            <input type="password" name="password" id="password"
                                class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                                placeholder="Kosongkan jika tidak diubah">
                            @error('password')
                                <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-xs font-medium text-gray-500">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 mt-4">
                    <a href="{{ route('admin.guru.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

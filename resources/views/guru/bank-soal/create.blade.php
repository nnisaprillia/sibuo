<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Bank Soal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Buat Bank Soal Baru</h1>

                    <form action="{{ route('guru.bank-soal.store') }}" method="POST" class="space-y-6">
                        @csrf

                        @if (!empty($warningNoAssignment) && $warningNoAssignment)
                            <div class="rounded-lg border border-yellow-300 bg-yellow-50 text-yellow-800 p-4">
                                <p class="text-sm">Tidak ada mata pelajaran yang ditugaskan untuk akun Anda saat ini, sehingga semua mata pelajaran ditampilkan.</p>
                            </div>
                        @endif

                        <div>
                            <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select id="mata_pelajaran_id" name="mata_pelajaran_id" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('mata_pelajaran_id') border-red-500 @enderror">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach ($mataPelajarans as $mp)
                                    <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>
                                        {{ $mp->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nama Bank Soal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_bank" name="nama_bank" value="{{ old('nama_bank') }}" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nama_bank') border-red-500 @enderror"
                                placeholder="Contoh: UAS Matematika Kelas X">
                            @error('nama_bank')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="durasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Durasi (menit) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="durasi" name="durasi" value="{{ old('durasi', 60) }}" required min="1" max="480"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('durasi') border-red-500 @enderror"
                                placeholder="60">
                            @error('durasi')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jadwal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Jadwal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="jadwal_mulai" name="jadwal_mulai" value="{{ old('jadwal_mulai') }}" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('jadwal_mulai') border-red-500 @enderror">
                            @error('jadwal_mulai')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jadwal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Jadwal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="jadwal_selesai" name="jadwal_selesai" value="{{ old('jadwal_selesai') }}" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('jadwal_selesai') border-red-500 @enderror">
                            @error('jadwal_selesai')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                Buat Bank Soal
                            </button>
                            <a href="{{ route('guru.bank-soal.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

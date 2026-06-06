<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Penugasan Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.penugasan-guru.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="guru_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guru</label>
                            <select name="guru_id" id="guru_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Pilih Guru</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }} ({{ $guru->email }})</option>
                                @endforeach
                            </select>
                            @error('guru_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mataPelajarans as $mp)
                                    <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>{{ $mp->nama }}</option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.penugasan-guru.index') }}" class="mr-4 text-gray-600 dark:text-gray-400">Batal</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

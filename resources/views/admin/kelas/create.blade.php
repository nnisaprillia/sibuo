<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.kelas.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="nama_kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                            @error('nama_kelas')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="tingkat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tingkat</label>
                            <select name="tingkat" id="tingkat" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Pilih Tingkat</option>
                                <option value="SD" {{ old('tingkat') === 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('tingkat') === 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('tingkat') === 'SMA' ? 'selected' : '' }}>SMA</option>
                            </select>
                            @error('tingkat')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.kelas.index') }}" class="mr-4 text-gray-600 dark:text-gray-400">Batal</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

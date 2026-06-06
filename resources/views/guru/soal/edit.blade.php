<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Soal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Edit Soal</h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Bank Soal: {{ $bankSoal->nama_bank }}</p>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded text-red-700 dark:text-red-200">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('guru.soal.update', [$bankSoal->id, $soal->id]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="pertanyaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="pertanyaan" name="pertanyaan" required rows="4"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('pertanyaan') border-red-500 @enderror">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
                            @error('pertanyaan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="pilihan_a" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Pilihan A <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="pilihan_a" name="pilihan_a" value="{{ old('pilihan_a', $soal->pilihan_a) }}" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('pilihan_a') border-red-500 @enderror">
                                @error('pilihan_a')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pilihan_b" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Pilihan B <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="pilihan_b" name="pilihan_b" value="{{ old('pilihan_b', $soal->pilihan_b) }}" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('pilihan_b') border-red-500 @enderror">
                                @error('pilihan_b')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pilihan_c" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Pilihan C <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="pilihan_c" name="pilihan_c" value="{{ old('pilihan_c', $soal->pilihan_c) }}" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('pilihan_c') border-red-500 @enderror">
                                @error('pilihan_c')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pilihan_d" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Pilihan D <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="pilihan_d" name="pilihan_d" value="{{ old('pilihan_d', $soal->pilihan_d) }}" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('pilihan_d') border-red-500 @enderror">
                                @error('pilihan_d')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="jawaban_benar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Jawaban Benar <span class="text-red-500">*</span>
                            </label>
                            <select id="jawaban_benar" name="jawaban_benar" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('jawaban_benar') border-red-500 @enderror">
                                <option value="a" {{ old('jawaban_benar', $soal->jawaban_benar) == 'a' ? 'selected' : '' }}>A</option>
                                <option value="b" {{ old('jawaban_benar', $soal->jawaban_benar) == 'b' ? 'selected' : '' }}>B</option>
                                <option value="c" {{ old('jawaban_benar', $soal->jawaban_benar) == 'c' ? 'selected' : '' }}>C</option>
                                <option value="d" {{ old('jawaban_benar', $soal->jawaban_benar) == 'd' ? 'selected' : '' }}>D</option>
                            </select>
                            @error('jawaban_benar')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                Perbarui Soal
                            </button>
                            <a href="{{ route('guru.bank-soal.show', $bankSoal->id) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

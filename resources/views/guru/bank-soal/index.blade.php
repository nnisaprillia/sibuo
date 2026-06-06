<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bank Soal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Bank Soal</h1>
                        <a href="{{ route('guru.bank-soal.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            + Buat Bank Soal Baru
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded text-green-700 dark:text-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($bankSoals->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Belum ada bank soal. Silakan buat yang baru.</p>
                    @else
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Bank Soal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mata Pelajaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah Soal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Durasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jadwal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($bankSoals as $bank)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $bank->nama_bank }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $bank->mataPelajaran->nama }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $bank->soal->count() }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $bank->durasi }} menit</td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $bank->jadwal_mulai->format('d M Y H:i') }} - {{ $bank->jadwal_selesai->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <a href="{{ route('guru.bank-soal.show', $bank->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Lihat</a>
                                                <a href="{{ route('guru.bank-soal.edit', $bank->id) }}" class="ml-3 text-yellow-600 hover:text-yellow-900 dark:text-yellow-400">Edit</a>
                                                <form action="{{ route('guru.bank-soal.destroy', $bank->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ml-3 text-red-600 hover:text-red-900 dark:text-red-400">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $bankSoals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-blue-500 text-white p-4 rounded">
                            <h3>Guru</h3>
                            <p>{{ \App\Models\User::where('role', 'guru')->count() }}</p>
                        </div>
                        <div class="bg-green-500 text-white p-4 rounded">
                            <h3>Siswa</h3>
                            <p>{{ \App\Models\User::where('role', 'siswa')->count() }}</p>
                        </div>
                        <div class="bg-yellow-500 text-white p-4 rounded">
                            <h3>Mata Pelajaran</h3>
                            <p>{{ \App\Models\MataPelajaran::count() }}</p>
                        </div>
                        <div class="bg-red-500 text-white p-4 rounded">
                            <h3>Kelas</h3>
                            <p>{{ \App\Models\Kelas::count() }}</p>
                        </div>
                    </div>

                    <p>Welcome to Admin Dashboard - Manage Guru, Siswa, Mata Pelajaran, Kelas, Jurusan, and Penugasan Guru.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
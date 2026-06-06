<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ExamHub') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Panel: Branding -->
        <div class="hidden md:flex md:w-1/2 bg-[#0f2744] p-12 flex-col justify-between relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>

            <!-- Brand Logo -->
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-white text-xl font-semibold tracking-tight">ExamHub</span>
            </div>

            <!-- Hero Text -->
            <div class="relative z-10 max-w-md">
                <h2 class="text-4xl font-medium text-white leading-tight">
                    Solusi Modern untuk <br>
                    <span class="text-[#60A5FA]">Ujian Online</span> Lebih Efisien.
                </h2>
                <p class="mt-4 text-white/50 text-sm leading-relaxed">
                    Platform ujian terintegrasi untuk mendukung proses belajar mengajar dengan sistem yang aman dan transparan.
                </p>
            </div>

            <!-- Statistics / Footer Info -->
            <div class="grid grid-cols-3 gap-8 relative z-10 border-t border-white/10 pt-8">
                <div>
                    <p class="text-2xl font-medium text-[#60A5FA]">100+</p>
                    <p class="text-white/40 text-[10px] uppercase tracking-widest font-semibold">Total Guru</p>
                </div>
                <div>
                    <p class="text-2xl font-medium text-[#60A5FA]">2.5k</p>
                    <p class="text-white/40 text-[10px] uppercase tracking-widest font-semibold">Total Siswa</p>
                </div>
                <div>
                    <p class="text-2xl font-medium text-[#60A5FA]">15+</p>
                    <p class="text-white/40 text-[10px] uppercase tracking-widest font-semibold">Mata Pelajaran</p>
                </div>
            </div>
        </div>

        <!-- Right Panel: Form -->
        <div class="flex-1 bg-white flex items-center justify-center p-8 md:p-16">
            <div class="w-full max-w-sm">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>

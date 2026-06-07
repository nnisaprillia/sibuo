<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIBUO') }} - Login</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoSIBUO.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Panel: Branding -->
        <div class="hidden md:flex md:w-1/2 bg-primary p-12 flex-col justify-between relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl"></div>

            <!-- Brand Logo -->
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 overflow-hidden">
                    <img src="{{ asset('assets/img/logoSIBUO.png') }}" alt="SIBUO" class="w-8 h-8 object-contain">
                </div>
                <span class="text-white text-xl font-semibold tracking-tight">SIBUO</span>
            </div>

            <!-- Hero Text -->
            <div class="relative z-10 max-w-md">
                <h2 class="text-4xl font-medium text-white leading-tight">
                    Solusi Modern untuk <br>
                    <span class="text-[#34D399]">Ujian Online</span> Lebih Efisien.
                </h2>
                <p class="mt-4 text-white/50 text-sm leading-relaxed">
                    Platform ujian terintegrasi untuk mendukung proses belajar mengajar dengan sistem yang aman dan transparan.
                </p>
            </div>

            <!-- Statistics / Footer Info -->
            <div class="grid grid-cols-3 gap-8 relative z-10 border-t border-white/10 pt-8">
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

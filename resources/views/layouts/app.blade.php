<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIBUO') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoSIBUO.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
    @stack('styles')
</head>
<body class="flex h-screen bg-[#F9FAFB] font-sans text-gray-900 overflow-hidden antialiased">
    
    @include('layouts.sidebar', ['active' => $active ?? ''])

    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <x-topbar :title="$title ?? 'Dashboard'">
            @slot('breadcrumb')
                @yield('breadcrumb')
            @endslot
        </x-topbar>

        <main class="flex-1 overflow-y-auto p-5 md:p-6 custom-scrollbar">
            <x-flash-message />
            
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>

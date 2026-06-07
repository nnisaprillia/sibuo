@extends('layouts.auth')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-medium text-gray-900">Masuk ke akun</h1>
        <p class="text-xs text-gray-500 mt-1">Masukkan Email dan Password Anda</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-xs font-medium text-green-600 bg-green-50 p-3 rounded-lg border border-green-100">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="block text-xs font-medium text-gray-500">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full h-10 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors @error('email') border-red-500 @enderror"
                placeholder="nama@contoh.com">
            @error('email')
                <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-medium text-gray-500">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] text-emerald-500 hover:text-emerald-600 font-medium" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative" x-data="{ show: false }">
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                    class="w-full h-10 px-3 pr-10 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-emerald-400 focus:outline-none transition-colors @error('password') border-red-500 @enderror"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            @error('password')
                <p class="text-[10px] text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="w-3.5 h-3.5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 bg-gray-50">
            <label for="remember_me" class="ml-2 text-[10px] text-gray-500">Tetap masuk di perangkat ini</label>
        </div>

        <button type="submit" class="w-full h-10 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-dark transition-colors flex items-center justify-center gap-2 shadow-lg shadow-emerald-900/10">
            Masuk Sekarang
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </form>
    
    <div class="mt-10 text-center">
        <p class="text-[10px] text-gray-400">SIBUO &copy; 2026. All rights reserved.</p>
    </div>
@endsection

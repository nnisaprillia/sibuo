@props(['active' => ''])

<aside class="w-44 bg-primary flex flex-col h-screen sticky top-0 z-20">
    <!-- Brand -->
    <div class="flex items-center gap-2 px-4 py-5 border-b border-white/10">
        <div class="w-7 h-7 bg-white rounded-lg flex items-center justify-center overflow-hidden">
            <img src="{{ asset('assets/img/logoSIBUO.png') }}" alt="SIBUO" class="w-5 h-5 object-contain">
        </div>
        <span class="text-white text-sm font-medium">SIBUO</span>
    </div>

    <!-- Nav items -->
    <nav class="flex flex-col flex-1 py-3 overflow-y-auto custom-scrollbar">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'dashboard' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>
        
        <a href="{{ route('admin.guru.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'guru' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Guru
        </a>

        <a href="{{ route('admin.siswa.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'siswa' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Siswa
        </a>

        <p class="px-4 pt-4 pb-1 text-[10px] text-white/30 uppercase tracking-widest font-semibold">Akademik</p>

        <a href="{{ route('admin.mata-pelajaran.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'mata-pelajaran' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Mata Pelajaran
        </a>

        <a href="{{ route('admin.kelas.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'kelas' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Kelas
        </a>

        <a href="{{ route('admin.jurusan.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'jurusan' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Jurusan
        </a>

        <a href="{{ route('admin.penugasan-guru.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'penugasan-guru' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            Penugasan Guru
        </a>

        <div class="mt-auto border-t border-white/10 py-2">
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'profile' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-white/50 hover:text-white/80 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </nav>
</aside>

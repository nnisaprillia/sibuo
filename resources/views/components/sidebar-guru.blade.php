@props(['active' => ''])

<aside class="w-48 bg-primary flex flex-col h-screen sticky top-0 z-20">
    <!-- Brand -->
    <div class="px-4 py-5 border-b border-white/10">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-emerald-500 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <span class="text-white text-sm font-medium">ExamHub</span>
        </div>
        
        <div class="mt-2">
            <p class="text-xs text-white font-medium truncate">{{ Auth::user()->name }}</p>
            <p class="text-[10px] text-white/40 truncate">Guru Pengampu</p>
        </div>
    </div>

    <!-- Nav items -->
    <nav class="flex flex-col flex-1 py-3">
        <p class="px-4 pt-2 pb-1 text-[10px] text-white/30 uppercase tracking-widest font-semibold">Menu</p>
        
        <a href="{{ route('guru.dashboard') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'dashboard' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>
        
        <a href="{{ route('guru.bank-soal.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'bank-soal' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Bank Soal
        </a>

        <a href="{{ route('guru.hasil-ujian.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-xs transition-colors {{ $active === 'hasil-ujian' ? 'text-emerald-300 bg-emerald-500/20 border-r-2 border-emerald-400' : 'text-white/50 hover:text-white/80 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Hasil Ujian
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

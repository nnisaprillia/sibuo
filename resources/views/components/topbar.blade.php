@props(['title' => ''])

<header class="h-14 lg:h-12 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-5 sticky top-0 z-10">
    <div class="flex items-center gap-2 lg:gap-3">
        <!-- Hamburger Menu Button (Mobile) -->
        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        
        <div class="flex flex-col lg:flex-row lg:items-center gap-0 lg:gap-3">
            <h1 class="text-xs lg:text-sm font-bold text-gray-900 truncate max-w-[150px] lg:max-w-none">{{ $title }}</h1>
            <div class="hidden lg:block">
                {{ $breadcrumb ?? '' }}
            </div>
        </div>
    </div>
    
    <div class="flex items-center gap-2 lg:gap-3">
        <div class="flex flex-col items-end mr-1 lg:mr-2">
            <span class="text-[10px] lg:text-xs font-bold text-gray-900 truncate max-w-[80px] lg:max-w-none">{{ Auth::user()->name }}</span>
            <span class="text-[9px] lg:text-[10px] text-gray-500 capitalize leading-none">{{ Auth::user()->role }}</span>
        </div>
        <div class="w-8 h-8 lg:w-8 lg:h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </div>
</header>

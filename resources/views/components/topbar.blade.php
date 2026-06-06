@props(['title' => ''])

<header class="h-12 bg-white border-b border-gray-200 flex items-center justify-between px-5 sticky top-0 z-10">
    <div class="flex items-center gap-3">
        <h1 class="text-sm font-medium text-gray-900">{{ $title }}</h1>
        {{ $breadcrumb ?? '' }}
    </div>
    
    <div class="flex items-center gap-3">
        <div class="flex flex-col items-end mr-2">
            <span class="text-xs font-medium text-gray-900">{{ Auth::user()->name }}</span>
            <span class="text-[10px] text-gray-500 capitalize">{{ Auth::user()->role }}</span>
        </div>
        <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </div>
</header>

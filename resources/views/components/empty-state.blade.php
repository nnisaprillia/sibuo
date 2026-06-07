@props(['entity', 'route' => null])

<div class="py-12 text-center">
    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
    </div>
    <p class="text-sm text-gray-400 font-medium">Belum ada data {{ $entity }}.</p>
    @if($route)
        <a href="{{ $route }}" class="mt-2 inline-block text-xs text-blue-500 hover:text-blue-600 transition-colors">
            + Tambah {{ $entity }} pertama
        </a>
    @endif
</div>

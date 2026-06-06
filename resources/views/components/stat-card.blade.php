@props(['label', 'value', 'info' => null, 'icon' => null])

<div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">{{ $label }}</p>
            <p class="text-2xl font-medium text-gray-900 leading-none">{{ $value }}</p>
        </div>
        @if($icon)
            <div class="p-2 bg-gray-50 rounded-lg text-gray-400">
                {{ $icon }}
            </div>
        @endif
    </div>
    @if($info)
        <span class="inline-block mt-3 text-[10px] font-medium px-2 py-0.5 rounded bg-blue-50 text-blue-700">
            {{ $info }}
        </span>
    @endif
</div>

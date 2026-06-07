@props(['type' => 'info'])

@php
    $classes = [
        'success' => 'bg-[#F0FDF4] text-[#166534]',
        'warning' => 'bg-[#FFFBEB] text-[#92400E]',
        'danger' => 'bg-[#FEF2F2] text-[#991B1B]',
        'info' => 'bg-[#EFF6FF] text-[#1D4ED8]',
        'primary' => 'bg-blue-50 text-blue-700',
    ][$type] ?? 'bg-gray-100 text-gray-700';
@endphp

<span {{ $attributes->merge(['class' => "text-[10px] font-medium px-2 py-0.5 rounded $classes"]) }}>
    {{ $slot }}
</span>

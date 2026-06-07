@props(['type' => 'info'])

@php
    $classes = [
        'success' => 'bg-[#F0FDF4] text-[#166534]',
        'warning' => 'bg-[#FFFBEB] text-[#92400E]',
        'danger' => 'bg-[#FEF2F2] text-[#991B1B]',
        'info' => 'bg-[#ECFDF5] text-[#047857]',
        'primary' => 'bg-emerald-50 text-emerald-700',
    ][$type] ?? 'bg-gray-100 text-gray-700';
@endphp

<span {{ $attributes->merge(['class' => "text-[10px] font-medium px-2 py-0.5 rounded $classes"]) }}>
    {{ $slot }}
</span>

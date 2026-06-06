@php
    $role = Auth::user()->role;
    $active = $active ?? '';
@endphp

@if($role === 'admin')
    <x-sidebar-admin :active="$active" />
@elseif($role === 'guru')
    <x-sidebar-guru :active="$active" />
@endif

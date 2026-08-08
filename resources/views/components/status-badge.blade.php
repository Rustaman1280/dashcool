@props(['status' => 'menunggu'])

@php
    $normalized = strtolower(trim($status));
    
    $classes = match($normalized) {
        'menunggu', 'pending' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20',
        'diverifikasi', 'verified' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20',
        'diterima', 'accepted' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20',
        'ditolak', 'rejected' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-600/20',
        default => 'bg-gray-50 text-gray-700 ring-1 ring-gray-600/20'
    };

    $label = match($normalized) {
        'menunggu', 'pending' => 'Menunggu',
        'diverifikasi', 'verified' => 'Diverifikasi',
        'diterima', 'accepted' => 'Diterima',
        'ditolak', 'rejected' => 'Ditolak',
        default => ucfirst($status)
    };

    $dotColor = match($normalized) {
        'menunggu', 'pending' => 'bg-amber-500',
        'diverifikasi', 'verified' => 'bg-blue-500',
        'diterima', 'accepted' => 'bg-emerald-500',
        'ditolak', 'rejected' => 'bg-rose-500',
        default => 'bg-gray-500'
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-x-1.5 px-2.5 py-1 rounded-full text-xs font-medium ' . $classes]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dotColor }}"></span>
    {{ $label }}
</span>

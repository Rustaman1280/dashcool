@props([
    'href' => '#',
    'active' => false,
    'label' => '',
    'badge' => null,
    'icon' => null
])

@php
    $baseClasses = 'group flex items-center gap-x-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-colors duration-150';
    
    $activeClasses = $active 
        ? 'bg-slate-900 text-white font-semibold shadow-sm' 
        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80';
        
    $iconClasses = $active
        ? 'text-white'
        : 'text-slate-400 group-hover:text-slate-700';
@endphp

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => "{$baseClasses} {$activeClasses}"]) }}
   x-bind:title="isCollapsed ? '{{ $label }}' : ''">
    
    <div class="flex-shrink-0 flex items-center justify-center w-6 h-6 transition-colors duration-150 {{ $iconClasses }}">
        @if ($icon)
            {!! $icon !!}
        @else
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        @endif
    </div>

    <span x-show="!isCollapsed" 
          x-transition:enter="transition ease-out duration-150"
          x-transition:enter-start="opacity-0 scale-95"
          x-transition:enter-end="opacity-100 scale-100"
          class="truncate flex-1">
        {{ $label }}
    </span>

    @if ($badge)
        <span x-show="!isCollapsed" 
              class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $active ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-600' }}">
            {{ $badge }}
        </span>
    @endif
</a>

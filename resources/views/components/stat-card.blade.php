@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'indigo', // indigo, blue, emerald, rose, amber
    'change' => null,
    'changeType' => 'increase', // increase, decrease, neutral
    'subtitle' => null
])

@php
    $colorClasses = [
        'indigo' => [
            'bg' => 'bg-indigo-50/80 text-indigo-600 border-indigo-100',
            'ring' => 'group-hover:border-indigo-200',
            'accent' => 'text-indigo-600',
        ],
        'blue' => [
            'bg' => 'bg-blue-50/80 text-blue-600 border-blue-100',
            'ring' => 'group-hover:border-blue-200',
            'accent' => 'text-blue-600',
        ],
        'emerald' => [
            'bg' => 'bg-emerald-50/80 text-emerald-600 border-emerald-100',
            'ring' => 'group-hover:border-emerald-200',
            'accent' => 'text-emerald-600',
        ],
        'rose' => [
            'bg' => 'bg-rose-50/80 text-rose-600 border-rose-100',
            'ring' => 'group-hover:border-rose-200',
            'accent' => 'text-rose-600',
        ],
        'amber' => [
            'bg' => 'bg-amber-50/80 text-amber-600 border-amber-100',
            'ring' => 'group-hover:border-amber-200',
            'accent' => 'text-amber-600',
        ],
    ];

    $currentColor = $colorClasses[$color] ?? $colorClasses['indigo'];
@endphp

<div {{ $attributes->merge(['class' => 'group relative bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5']) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 tracking-wide">{{ $title }}</p>
            <h3 class="mt-2 text-3xl font-bold text-gray-900 tracking-tight">{{ $value }}</h3>
        </div>
        
        <div class="flex items-center justify-center w-12 h-12 rounded-xl border {{ $currentColor['bg'] }} transition-transform duration-200 group-hover:scale-110">
            @if ($icon)
                {!! $icon !!}
            @else
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            @endif
        </div>
    </div>

    @if ($change || $subtitle)
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
            @if ($change)
                <div class="flex items-center gap-1 font-medium {{ $changeType === 'increase' ? 'text-emerald-600' : ($changeType === 'decrease' ? 'text-rose-600' : 'text-gray-500') }}">
                    @if ($changeType === 'increase')
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                        </svg>
                    @elseif ($changeType === 'decrease')
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>
                    @endif
                    <span>{{ $change }}</span>
                </div>
            @endif

            @if ($subtitle)
                <span class="text-gray-400 font-normal ml-auto">{{ $subtitle }}</span>
            @endif
        </div>
    @endif
</div>

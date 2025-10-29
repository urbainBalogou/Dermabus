@props(['title' => 'Statistique', 'value' => 0, 'icon' => 'chart-bar'])

@php
    $icons = [
        'users' => 'M3 7l9 4 9-4-9-4-9 4zm0 6l9 4 9-4',
        'clipboard' => 'M9 3h6a2 2 0 0 1 2 2v16l-5-3-5 3V5a2 2 0 0 1 2-2z',
        'hand-holding-medical' => 'M3 13h6l3-3 3 6 6-3',
        'seedling' => 'M5 12c0-4.418 3.582-8 8-8a8 8 0 0 1 8 8h-5a3 3 0 0 0-3-3',
        'megaphone' => 'M3 10l9-6v16l-9-6zm12-4h2a4 4 0 0 1 4 4v4a4 4 0 0 1-4 4h-2',
    ];

    $path = $icons[$icon] ?? $icons['clipboard'];
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-500">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $value }}</p>
        </div>
        <div class="rounded-full bg-emerald-100 p-3 text-emerald-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
            </svg>
        </div>
    </div>
</div>

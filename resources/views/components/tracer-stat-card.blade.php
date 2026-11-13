@props(['border', 'icon', 'value', 'label', 'sublabel' => null])

<div class="bg-white rounded-xl border-l-4 {{ $border }} shadow-sm p-5">
    <div class="text-xl mb-2">{{ $icon }}</div>
    <p class="text-2xl font-bold text-sky-900">{{ $value }}</p>
    <p class="text-slate-700 text-sm font-medium">{{ $label }}</p>
    @if ($sublabel)
        <p class="text-slate-400 text-xs mt-0.5">{{ $sublabel }}</p>
    @endif
</div>

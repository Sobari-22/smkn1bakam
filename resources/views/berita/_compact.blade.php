{{-- Baris berita ringkas (judul + foto kecil). Variabel: $item (News), $kickerColors (array). --}}
@php $img = $item->thumbnail ? asset('storage/'.$item->thumbnail) : null; @endphp
<article class="group py-4 border-b border-stone-300 last:border-b-0">
    <a href="{{ route('berita.show', $item) }}" class="flex items-start gap-4">
        <div class="min-w-0 flex-1">
            <p class="kicker {{ $kickerColors[$item->category] ?? 'text-red-700' }}">{{ $item->category ?? 'Berita' }}</p>
            <h3 class="hl headline text-base leading-snug mt-1">{{ $item->title }}</h3>
            <p class="sans text-xs text-stone-500 mt-1.5">
                {{ $item->published_at->locale('id')->translatedFormat('j F Y') }} · ⏱ {{ $item->reading_time }} mnt
            </p>
        </div>
        @if ($img)
            <div class="shrink-0 w-24 h-20 overflow-hidden bg-stone-200">
                <img src="{{ $img }}" alt="" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            </div>
        @endif
    </a>
</article>

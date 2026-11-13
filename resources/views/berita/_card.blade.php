{{-- Kartu berita vertikal ala koran. Variabel: $item (News), $kickerColors (array). --}}
@php $img = $item->thumbnail ? asset('storage/'.$item->thumbnail) : null; @endphp
<article class="group h-full flex flex-col">
    <a href="{{ route('berita.show', $item) }}" class="block">
        <div class="aspect-[4/3] overflow-hidden bg-stone-200 mb-3">
            @if ($img)
                <img src="{{ $img }}" alt="{{ $item->title }}" loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center text-stone-400 text-4xl">📰</div>
            @endif
        </div>
        <p class="kicker {{ $kickerColors[$item->category] ?? 'text-red-700' }}">
            {{ $item->category ?? 'Berita' }}
            <span class="font-normal normal-case tracking-normal text-stone-400">· {{ $item->published_at->locale('id')->translatedFormat('j F Y') }}</span>
        </p>
        <h3 class="hl headline text-xl leading-snug mt-1.5">{{ $item->title }}</h3>
    </a>
    <p class="text-stone-600 text-[15px] leading-relaxed mt-2 line-clamp-3">{{ $item->teaser(200) }}</p>
    <div class="mt-auto pt-3 flex items-center justify-between gap-3">
        <span class="sans text-xs text-stone-500">
            ⏱ {{ $item->reading_time }} menit baca
            @if (($item->approved_comments_count ?? 0) > 0) · 💬 {{ $item->approved_comments_count }} @endif
        </span>
        <a href="{{ route('berita.show', $item) }}" class="kicker text-red-700 hover:text-red-900 whitespace-nowrap">Baca »</a>
    </div>
</article>

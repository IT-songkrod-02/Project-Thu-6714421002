<div class="card-lux group overflow-hidden transition hover:-translate-y-1 hover:shadow-[0_10px_32px_rgba(212,175,55,0.25)]">
    <div class="relative aspect-[4/3] overflow-hidden bg-gold-champagne">
        <a href="{{ route('reels.show', $reel) }}">
            <img src="{{ $reel->cover_url }}" alt="{{ $reel->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        </a>
        <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs text-gold-dark shadow">{{ $reel->type_label }}</span>
        @if($reel->status !== 'available')
            <span class="absolute right-3 top-3 rounded-full bg-ink/80 px-3 py-1 text-xs text-white">{{ $reel->status === 'sold' ? 'ขายแล้ว' : 'ซ่อนอยู่' }}</span>
        @elseif(isset($favoriteIds))
            <form method="POST" action="{{ route('favorites.toggle', $reel) }}" class="absolute right-3 top-3">
                @csrf
                <button class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-lg shadow hover:scale-110 transition"
                        title="รายการโปรด">{!! in_array($reel->id, $favoriteIds) ? '<span class="text-red-500">♥</span>' : '<span class="text-stone-400">♡</span>' !!}</button>
            </form>
        @endif
    </div>
    <div class="p-5">
        <p class="text-xs uppercase tracking-widest text-gold-dark">{{ $reel->brand->name }}</p>
        <a href="{{ route('reels.show', $reel) }}" class="mt-1 block truncate font-serif text-lg text-ink hover:text-gold-dark">{{ $reel->title }}</a>
        <p class="mt-1 truncate text-sm text-stone-500">{{ $reel->model_name }} · สภาพ {{ $reel->condition_percentage }}%</p>
        <div class="mt-4 flex items-end justify-between">
            <div class="font-serif text-xl font-semibold text-gold-dark">฿{{ number_format($reel->price) }}</div>
            <div class="text-right text-xs text-stone-400">
                📍 {{ $reel->location_province }}<br>
                @if(optional($reel->user)->is_verified_seller)<span class="text-gold-dark">✓ Verified</span>@endif
            </div>
        </div>
    </div>
</div>
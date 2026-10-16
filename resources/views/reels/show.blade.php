@extends('layouts.app')
@section('title', $reel->title)

@section('content')
@php
    $isOwner = auth()->id() === $reel->user_id;
    $stars = fn ($r) => str_repeat('★', (int) round($r)) . str_repeat('☆', 5 - (int) round($r));
    $images = $reel->images->map(fn ($i) => $i->url)->all() ?: [$reel->cover_url];
@endphp

<div class="mx-auto max-w-7xl px-4 py-10">
    <a href="{{ route('reels.index') }}" class="text-sm text-stone-500 hover:text-gold-dark">← กลับไปตลาดรอก</a>

    <div class="mt-6 grid gap-10 lg:grid-cols-2">
        {{-- Gallery --}}
        <div x-data="{ main: '{{ $images[0] }}' }">
            <div class="card-lux overflow-hidden"><img :src="main" class="aspect-[4/3] w-full object-cover"></div>
            <div class="mt-3 grid grid-cols-5 gap-2">
                @foreach($images as $img)
                    <button type="button" @click="main = '{{ $img }}'" class="overflow-hidden rounded-lg border border-gold/30 hover:border-gold">
                        <img src="{{ $img }}" class="aspect-square w-full object-cover">
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Info --}}
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-gold-dark">{{ $reel->brand->name }} · {{ $reel->type_label }}</p>
            <h1 class="mt-2 font-serif text-3xl text-ink">{{ $reel->title }}</h1>
            <p class="mt-1 text-stone-500">{{ $reel->model_name }}</p>
            <div class="mt-5 font-serif text-4xl font-semibold text-gold-dark">฿{{ number_format($reel->price) }}</div>

            <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                @foreach([
                    'สภาพ' => $reel->condition_percentage . '%',
                    'อัตราทด' => $reel->gear_ratio ?? '-',
                    'ลูกปืน' => $reel->ball_bearings ?? '-',
                    'แรงเบรกสูงสุด' => $reel->max_drag_kg ? $reel->max_drag_kg . ' kg' : '-',
                    'น้ำหนัก' => $reel->weight_g ? $reel->weight_g . ' g' : '-',
                    'ที่ตั้ง' => $reel->location_province . ($reel->location_district ? ' / ' . $reel->location_district : ''),
                    'เข้าชม' => number_format($reel->view_count) . ' ครั้ง',
                    'สถานะ' => ['available'=>'พร้อมขาย','sold'=>'ขายแล้ว','hidden'=>'ซ่อนอยู่'][$reel->status],
                ] as $k => $val)
                    <div class="rounded-lg border border-gold/20 bg-gold-champagne/40 px-4 py-3">
                        <div class="text-xs text-stone-400">{{ $k }}</div>
                        <div class="font-medium">{{ $val }}</div>
                    </div>
                @endforeach
            </div>

            @if($reel->description)
                <div class="mt-6">
                    <h3 class="font-serif text-lg">รายละเอียด</h3>
                    <p class="mt-2 whitespace-pre-line leading-relaxed text-stone-600">{{ $reel->description }}</p>
                </div>
            @endif

            {{-- Actions --}}
            <div class="mt-8 flex flex-wrap gap-3">
                @if($isOwner)
                    <a href="{{ route('reels.edit', $reel) }}" class="btn-gold">แก้ไขประกาศ</a>
                    <form method="POST" action="{{ route('reels.destroy', $reel) }}" onsubmit="return confirm('ยืนยันการลบ?')">
                        @csrf @method('DELETE')
                        <button class="rounded-lg border border-red-300 px-6 py-3 text-red-500 hover:bg-red-50">ลบ</button>
                    </form>
                @else
                    <a href="{{ route('chat.show', [$reel, $reel->user]) }}" class="btn-gold">💬 แชทกับผู้ขาย</a>
                    <form method="POST" action="{{ route('favorites.toggle', $reel) }}">@csrf
                        <button class="btn-outline">{{ $isFavorite ? '♥ เก็บแล้ว' : '♡ เก็บเข้ารายการโปรด' }}</button>
                    </form>
                @endif
            </div>

            {{-- Seller card --}}
            <div class="card-lux mt-8 p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full text-xl font-semibold text-white"
                         style="background:linear-gradient(135deg,#B8962E,#F1D77A)">{{ mb_substr($reel->user->name, 0, 1) }}</div>
                    <div>
                        <div class="flex items-center gap-2 font-medium">
                            {{ $reel->user->name }}
                            @if($reel->user->is_verified_seller)
                                <span class="rounded-full border border-gold bg-gold-champagne px-2 py-0.5 text-[11px] text-gold-dark">✓ Verified Seller</span>
                            @endif
                        </div>
                        <div class="text-sm text-gold">{{ $stars($reel->user->seller_rating) }}
                            <span class="text-stone-400">{{ number_format($reel->user->seller_rating, 1) }} ({{ $reel->user->reviewsReceived->count() }} รีวิว)</span>
                        </div>
                    </div>
                </div>
                @if(!$isOwner)
                <div class="mt-4 text-sm text-stone-500">
                    @if($reel->user->line_id)<div>LINE: {{ $reel->user->line_id }}</div>@endif
                    @if($reel->user->phone_number)<div>โทร: {{ $reel->user->phone_number }}</div>@endif
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    <section class="mt-14">
        <h2 class="font-serif text-2xl">รีวิวผู้ขาย</h2>
        <div class="mt-5 grid gap-8 lg:grid-cols-2">
            <div class="space-y-4">
                @forelse($reel->user->reviewsReceived->sortByDesc('created_at') as $rv)
                    <div class="card-lux p-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium">{{ $rv->reviewer->name }}</span>
                            <span class="text-gold">{{ $stars($rv->rating) }}</span>
                        </div>
                        @if($rv->comment)<p class="mt-2 text-sm text-stone-600">{{ $rv->comment }}</p>@endif
                    </div>
                @empty
                    <p class="text-sm text-stone-400">ยังไม่มีรีวิว</p>
                @endforelse
            </div>

            @unless($isOwner)
            <form method="POST" action="{{ route('reviews.store', $reel->user) }}" class="card-lux space-y-4 p-6">
                @csrf
                <h3 class="font-serif text-lg">{{ $myReview ? 'แก้ไขรีวิวของคุณ' : 'ให้คะแนนผู้ขายรายนี้' }}</h3>
                <select name="rating" class="input-lux">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected(optional($myReview)->rating == $i)>{{ str_repeat('★', $i) }} ({{ $i }})</option>
                    @endfor
                </select>
                <textarea name="comment" rows="3" class="input-lux" placeholder="เล่าประสบการณ์การซื้อขาย...">{{ optional($myReview)->comment }}</textarea>
                @error('rating')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                <button class="btn-gold">ส่งรีวิว</button>
            </form>
            @endunless
        </div>
    </section>
</div>
<style>[x-cloak]{display:none !important}</style>
@endsection
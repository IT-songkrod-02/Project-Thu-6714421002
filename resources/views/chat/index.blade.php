@extends('layouts.app')
@section('title', 'ข้อความ')
@section('content')
<div class="mx-auto max-w-3xl px-4 py-10">
    <h1 class="mb-8 font-serif text-3xl">ข้อความของฉัน</h1>
    <div class="space-y-3">
        @forelse($conversations as $c)
            <a href="{{ route('chat.show', [$c['reel'], $c['other']]) }}" class="card-lux flex items-center gap-4 p-4 transition hover:shadow-lg">
                <div class="flex h-12 w-12 items-center justify-center rounded-full font-semibold text-white"
                     style="background:linear-gradient(135deg,#B8962E,#F1D77A)">{{ mb_substr($c['other']->name, 0, 1) }}</div>
                <div class="min-w-0 flex-1">
                    <div class="flex justify-between">
                        <span class="font-medium">{{ $c['other']->name }}</span>
                        <span class="text-xs text-stone-400">{{ $c['last']->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="truncate text-xs text-gold-dark">{{ $c['reel']->title }}</div>
                    <div class="truncate text-sm text-stone-500">{{ $c['last']->message_text }}</div>
                </div>
                @if($c['unread'])<span class="rounded-full bg-gold px-2 py-0.5 text-xs text-white">{{ $c['unread'] }}</span>@endif
            </a>
        @empty
            <div class="card-lux p-16 text-center text-stone-400">ยังไม่มีการสนทนา กด "แชทกับผู้ขาย" จากหน้าสินค้าเพื่อเริ่มคุย</div>
        @endforelse
    </div>
</div>
@endsection
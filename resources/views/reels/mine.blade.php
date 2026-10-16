@extends('layouts.app')
@section('title', 'ประกาศของฉัน')
@section('content')
<div class="mx-auto max-w-7xl px-4 py-10">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="font-serif text-3xl">ประกาศของฉัน</h1>
        <a href="{{ route('reels.create') }}" class="btn-gold">+ ลงขายรอกใหม่</a>
    </div>

    @if($reels->count())
    <div class="space-y-4">
        @foreach($reels as $reel)
            <div class="card-lux flex flex-col gap-4 p-4 sm:flex-row sm:items-center">
                <img src="{{ $reel->cover_url }}" class="h-24 w-32 rounded-lg object-cover">
                <div class="flex-1">
                    <p class="text-xs uppercase tracking-widest text-gold-dark">{{ $reel->brand->name }}</p>
                    <a href="{{ route('reels.show', $reel) }}" class="font-serif text-lg hover:text-gold-dark">{{ $reel->title }}</a>
                    <p class="text-sm text-stone-500">฿{{ number_format($reel->price) }} · 👁 {{ $reel->view_count }} ·
                        <span class="{{ $reel->status === 'available' ? 'text-green-600' : 'text-stone-400' }}">
                            {{ ['available'=>'พร้อมขาย','sold'=>'ขายแล้ว','hidden'=>'ซ่อนอยู่'][$reel->status] }}
                        </span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('reels.edit', $reel) }}" class="btn-outline !px-4 !py-2 text-sm">แก้ไข</a>
                    <form method="POST" action="{{ route('reels.destroy', $reel) }}" onsubmit="return confirm('ยืนยันการลบประกาศนี้?')">
                        @csrf @method('DELETE')
                        <button class="rounded-lg border border-red-300 px-4 py-2 text-sm text-red-500 transition hover:bg-red-50">ลบ</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $reels->links() }}</div>
    @else
        <div class="card-lux p-16 text-center text-stone-400">คุณยังไม่มีประกาศขาย</div>
    @endif
</div>
@endsection
@extends('layouts.app')
@section('title', 'รายการโปรด')
@section('content')
<div class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="mb-8 font-serif text-3xl">รายการโปรดของฉัน</h1>
    @if($reels->count())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($reels as $reel) @include('reels._card', ['reel' => $reel]) @endforeach
        </div>
        <div class="mt-8">{{ $reels->links() }}</div>
    @else
        <div class="card-lux p-16 text-center text-stone-400">ยังไม่มีรายการโปรด กดหัวใจที่การ์ดสินค้าเพื่อเก็บไว้ดูทีหลัง</div>
    @endif
</div>
@endsection
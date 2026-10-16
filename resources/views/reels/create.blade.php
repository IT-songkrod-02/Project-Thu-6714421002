@extends('layouts.app')
@section('title', 'ลงขายรอก')
@section('content')
<div class="mx-auto max-w-7xl px-4 py-10">
    <div class="mb-8 text-center">
        <p class="text-xs uppercase tracking-[0.4em] text-gold-dark">Sell with elegance</p>
        <h1 class="mt-1 font-serif text-3xl">ลงขายรอกของคุณ</h1>
    </div>
    @include('reels._form')
</div>
@endsection
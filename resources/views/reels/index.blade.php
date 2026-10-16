@extends('layouts.app')
@section('title', 'ตลาดรอกตกปลา')

@section('content')
{{-- Hero + Search --}}
<section style="background: linear-gradient(180deg,#FAF6EA,#fff);" class="border-b border-gold/10">
    <div class="mx-auto max-w-7xl px-4 py-12 text-center">
        <p class="text-xs uppercase tracking-[0.4em] text-gold-dark">The Collection</p>
        <h1 class="mt-2 font-serif text-4xl text-ink">ตลาดรอกตกปลามือสอง</h1>
        <form method="GET" class="mx-auto mt-8 flex max-w-2xl gap-2">
            <input name="q" value="{{ request('q') }}" placeholder="ค้นหายี่ห้อ รุ่น หรือชื่อรอก..." class="input-lux !rounded-full !px-6 shadow-sm">
            <button class="btn-gold !rounded-full">ค้นหา</button>
        </form>
    </div>
</section>

<div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 lg:grid-cols-[260px_1fr]">
    {{-- Filter --}}
    <aside>
        <form method="GET" class="card-lux sticky top-24 space-y-4 p-5">
            <h3 class="font-serif text-lg">ตัวกรอง</h3>
            <input type="hidden" name="q" value="{{ request('q') }}">

            <div>
                <label class="label-lux">ยี่ห้อ</label>
                <select name="brand" class="input-lux">
                    <option value="">ทั้งหมด</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected(request('brand') == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label-lux">ประเภทรอก</label>
                <select name="type" class="input-lux">
                    <option value="">ทั้งหมด</option>
                    @foreach(\App\Models\Reel::TYPES as $k => $v)
                        <option value="{{ $k }}" @selected(request('type') == $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div><label class="label-lux">ราคาต่ำสุด</label><input type="number" name="min_price" value="{{ request('min_price') }}" class="input-lux"></div>
                <div><label class="label-lux">ราคาสูงสุด</label><input type="number" name="max_price" value="{{ request('max_price') }}" class="input-lux"></div>
            </div>
            <div>
                <label class="label-lux">สภาพขั้นต่ำ (%)</label>
                <input type="number" name="min_condition" min="1" max="100" value="{{ request('min_condition') }}" class="input-lux">
            </div>
            <div>
                <label class="label-lux">จังหวัด</label>
                <input name="province" value="{{ request('province') }}" class="input-lux" placeholder="เช่น ระยอง">
            </div>
            <div>
                <label class="label-lux">เรียงตาม</label>
                <select name="sort" class="input-lux">
                    <option value="">ใหม่ล่าสุด</option>
                    <option value="price_asc" @selected(request('sort')=='price_asc')>ราคา ต่ำ → สูง</option>
                    <option value="price_desc" @selected(request('sort')=='price_desc')>ราคา สูง → ต่ำ</option>
                    <option value="popular" @selected(request('sort')=='popular')>ยอดนิยม</option>
                </select>
            </div>
            <button class="btn-gold w-full">ใช้ตัวกรอง</button>
            <a href="{{ route('reels.index') }}" class="block text-center text-xs text-stone-400 hover:text-gold-dark">ล้างตัวกรอง</a>
        </form>
    </aside>

    {{-- Grid --}}
    <section>
        <p class="mb-5 text-sm text-stone-500">พบ {{ $reels->total() }} รายการ</p>
        @if($reels->count())
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($reels as $reel)
                    @include('reels._card', ['reel' => $reel])
                @endforeach
            </div>
            <div class="mt-10">{{ $reels->links() }}</div>
        @else
            <div class="card-lux p-16 text-center text-stone-400">ไม่พบรอกที่ตรงกับเงื่อนไข</div>
        @endif
    </section>
</div>
@endsection
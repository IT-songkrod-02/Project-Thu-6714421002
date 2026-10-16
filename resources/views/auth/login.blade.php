@extends('layouts.app')
@section('title', 'เข้าสู่ระบบ')

@section('content')
<div class="grid min-h-screen lg:grid-cols-2">
    {{-- ฝั่งซ้าย: Hero --}}
    <div class="relative hidden items-center justify-center overflow-hidden lg:flex"
         style="background: radial-gradient(circle at 20% 20%, #fff 0%, #FAF6EA 45%, #F1E2A6 100%);">
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full border border-gold/30"></div>
        <div class="absolute -bottom-32 -left-16 h-[28rem] w-[28rem] rounded-full border border-gold/20"></div>
        <div class="relative max-w-md px-10">
            <p class="mb-4 text-xs uppercase tracking-[0.4em] text-gold-dark">Pre-owned · Curated · Trusted</p>
            <h1 class="font-serif text-5xl leading-tight text-ink">
                The Finest <span class="gold-text">Reels</span>,<br>Second to None.
            </h1>
            <p class="mt-6 leading-relaxed text-stone-600">
                ตลาดซื้อขายรอกตกปลามือสองระดับพรีเมียม คัดสรรรอกคุณภาพจากนักตกปลาทั่วประเทศ
                พร้อมระบบผู้ขายที่ผ่านการยืนยันตัวตน
            </p>
            <div class="mt-10 flex gap-8 text-sm">
                <div><div class="font-serif text-2xl text-gold-dark">70+</div><div class="text-stone-500">แบรนด์รอก</div></div>
                <div><div class="font-serif text-2xl text-gold-dark">1:1</div><div class="text-stone-500">แชทตรงกับผู้ขาย</div></div>
                <div><div class="font-serif text-2xl text-gold-dark">✓</div><div class="text-stone-500">Verified Seller</div></div>
            </div>
        </div>
    </div>

    {{-- ฝั่งขวา: ฟอร์ม --}}
    <div class="flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <div class="mb-10 text-center">
                <div class="font-serif text-4xl font-semibold"><span class="gold-text">REEL</span> &amp; CO.</div>
                <div class="mx-auto mt-3 h-px w-16 bg-gold"></div>
                <p class="mt-4 text-sm text-stone-500">เข้าสู่ระบบเพื่อเริ่มต้นใช้งาน</p>
            </div>

            <form method="POST" action="{{ route('login.post') }}" class="card-lux space-y-5 p-8">
                @csrf
                <div>
                    <label class="label-lux">อีเมล</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-lux" autofocus>
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label-lux">รหัสผ่าน</label>
                    <input type="password" name="password" class="input-lux">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-stone-500">
                    <input type="checkbox" name="remember" class="rounded border-stone-300 text-gold focus:ring-gold"> จดจำฉันไว้
                </label>
                <button class="btn-gold w-full">เข้าสู่ระบบ</button>
            </form>

            <p class="mt-6 text-center text-sm text-stone-500">
                ยังไม่มีบัญชี?
                <a href="{{ route('register') }}" class="font-medium text-gold-dark underline-offset-4 hover:underline">สมัครสมาชิก</a>
            </p>
            <p class="mt-8 text-center text-xs text-stone-400">ทดลอง: seller@reel.test / password</p>
        </div>
    </div>
</div>
@endsection
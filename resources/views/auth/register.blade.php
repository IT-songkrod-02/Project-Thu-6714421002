@extends('layouts.app')
@section('title', 'สมัครสมาชิก')

@section('content')
<div class="flex min-h-screen items-center justify-center px-6 py-12"
     style="background: linear-gradient(180deg,#fff 0%,#FAF6EA 100%);">
    <div class="w-full max-w-lg">
        <div class="mb-8 text-center">
            <div class="font-serif text-4xl font-semibold"><span class="gold-text">REEL</span> &amp; CO.</div>
            <div class="mx-auto mt-3 h-px w-16 bg-gold"></div>
            <h2 class="mt-4 font-serif text-xl">Create Your Account</h2>
        </div>

        <form method="POST" action="{{ route('register.post') }}" class="card-lux space-y-5 p-8">
            @csrf
            <div>
                <label class="label-lux">ชื่อ-นามสกุล *</label>
                <input name="name" value="{{ old('name') }}" class="input-lux">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label-lux">อีเมล *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input-lux">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label-lux">เบอร์โทร</label>
                    <input name="phone_number" value="{{ old('phone_number') }}" class="input-lux">
                </div>
                <div>
                    <label class="label-lux">LINE ID</label>
                    <input name="line_id" value="{{ old('line_id') }}" class="input-lux">
                </div>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label-lux">รหัสผ่าน * (8 ตัวขึ้นไป)</label>
                    <input type="password" name="password" class="input-lux">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label-lux">ยืนยันรหัสผ่าน *</label>
                    <input type="password" name="password_confirmation" class="input-lux">
                </div>
            </div>
            <button class="btn-gold w-full">สมัครสมาชิก</button>
        </form>

        <p class="mt-6 text-center text-sm text-stone-500">
            มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}" class="font-medium text-gold-dark hover:underline">เข้าสู่ระบบ</a>
        </p>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'แชทกับ ' . $other->name)

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8">
    <a href="{{ route('chat.index') }}" class="text-sm text-stone-500 hover:text-gold-dark">← กล่องข้อความ</a>

    <div class="card-lux mt-4 overflow-hidden">
        {{-- Header: ผู้ขาย + สินค้า --}}
        <div class="flex items-center gap-4 border-b border-gold/20 bg-gold-champagne/60 p-4">
            <img src="{{ $reel->cover_url }}" class="h-14 w-14 rounded-lg object-cover">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 font-medium">{{ $other->name }}
                    @if($other->is_verified_seller)<span class="text-xs text-gold-dark">✓ Verified</span>@endif
                    <span id="status" class="ml-1 text-xs text-stone-400">● กำลังเชื่อมต่อ</span>
                </div>
                <a href="{{ route('reels.show', $reel) }}" class="block truncate text-sm text-gold-dark hover:underline">{{ $reel->title }} · ฿{{ number_format($reel->price) }}</a>
            </div>
        </div>

        <div id="messages" class="h-[28rem] space-y-3 overflow-y-auto bg-white p-5"></div>

        <form id="chat-form" class="flex gap-2 border-t border-gold/20 p-4">
            <input id="chat-input" autocomplete="off" maxlength="1000" placeholder="พิมพ์ข้อความ..." class="input-lux !rounded-full">
            <button class="btn-gold !rounded-full !px-6">ส่ง</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>
(() => {
    const me = {{ auth()->id() }};
    const box = document.getElementById('messages');
    const status = document.getElementById('status');
    const history = @json($history);

    function add(m) {
        const mine = Number(m.sender_id) === me;
        const row = document.createElement('div');
        row.className = 'flex ' + (mine ? 'justify-end' : 'justify-start');
        const bubble = document.createElement('div');
        bubble.className = 'max-w-[75%] rounded-2xl px-4 py-2 text-sm shadow-sm ' +
            (mine ? 'text-white' : 'bg-stone-100 text-stone-800');
        if (mine) bubble.style.backgroundImage = 'linear-gradient(135deg,#B8962E,#D4AF37)';
        const text = document.createElement('div');
        text.textContent = m.message_text;                       // กัน XSS
        const time = document.createElement('div');
        time.className = 'mt-1 text-[10px] opacity-70';
        time.textContent = new Date(m.created_at).toLocaleTimeString('th-TH', {hour:'2-digit', minute:'2-digit'});
        bubble.append(text, time); row.append(bubble); box.append(row);
        box.scrollTop = box.scrollHeight;
    }
    history.forEach(add);

    const socket = io(@json($chatUrl), { auth: { token: @json($token) } });

    socket.on('connect', () => {
        socket.emit('join', { reelId: {{ $reel->id }}, otherId: {{ $other->id }} }, (res) => {
            status.textContent = res.ok ? '● ออนไลน์' : '● ' + res.error;
            status.className = 'ml-1 text-xs ' + (res.ok ? 'text-green-500' : 'text-red-500');
        });
    });
    socket.on('disconnect', () => { status.textContent = '● ขาดการเชื่อมต่อ'; status.className = 'ml-1 text-xs text-red-500'; });
    socket.on('connect_error', () => { status.textContent = '● เชื่อมต่อไม่ได้ (เปิด Node server หรือยัง?)'; });
    socket.on('message:new', add);

    document.getElementById('chat-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (!text) return;
        socket.emit('send', { text }, (res) => { if (!res.ok) alert(res.error); });
        input.value = '';
    });
})();
</script>
@endpush
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'REEL & CO.') — Premium Pre-owned Fishing Reels</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { gold: { DEFAULT: '#D4AF37', light: '#F1D77A', dark: '#B8962E', champagne: '#FAF6EA' }, ink: '#1C1B19' },
                fontFamily: { sans: ['Prompt', 'sans-serif'], serif: ['"Playfair Display"', 'Prompt', 'serif'] },
            } }
        }
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .btn-gold { @apply inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 font-medium text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg; background-image: linear-gradient(135deg,#B8962E,#D4AF37 50%,#F1D77A); }
            .btn-outline { @apply inline-flex items-center justify-center gap-2 rounded-lg border border-gold px-6 py-3 font-medium text-gold-dark transition hover:bg-gold/10; }
            .card-lux { @apply rounded-xl border border-gold/20 bg-white shadow-[0_4px_24px_rgba(212,175,55,0.12)]; }
            .input-lux { @apply w-full rounded-lg border border-stone-200 bg-white px-4 py-2.5 text-stone-800 outline-none transition focus:border-gold focus:ring-2 focus:ring-gold/30; }
            .label-lux { @apply mb-1 block text-sm font-medium text-stone-600; }
            .gold-text { @apply bg-clip-text text-transparent; background-image: linear-gradient(135deg,#B8962E,#D4AF37 50%,#F1D77A); }
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-white font-sans text-stone-800 antialiased">

@auth
<header class="sticky top-0 z-40 border-b border-gold/20 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
        <a href="{{ route('reels.index') }}" class="font-serif text-2xl font-semibold tracking-wide">
            <span class="gold-text">REEL</span> <span class="text-ink">&amp; CO.</span>
        </a>
        <nav class="hidden items-center gap-7 text-sm md:flex">
            <a href="{{ route('reels.index') }}" class="hover:text-gold-dark">ตลาดรอก</a>
            <a href="{{ route('reels.mine') }}" class="hover:text-gold-dark">ประกาศของฉัน</a>
            <a href="{{ route('favorites.index') }}" class="hover:text-gold-dark">รายการโปรด</a>
            @php $unread = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count(); @endphp
            <a href="{{ route('chat.index') }}" class="relative hover:text-gold-dark">แชท
                @if($unread)<span class="absolute -right-4 -top-2 rounded-full bg-gold px-1.5 text-[10px] text-white">{{ $unread }}</span>@endif
            </a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ route('reels.create') }}" class="btn-gold !px-4 !py-2 text-sm">+ ลงขายรอก</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="text-sm text-stone-500 hover:text-gold-dark">ออกจากระบบ</button>
            </form>
        </div>
    </div>
    {{-- mobile nav --}}
    <nav class="flex justify-around border-t border-gold/10 py-2 text-xs md:hidden">
        <a href="{{ route('reels.index') }}">ตลาดรอก</a>
        <a href="{{ route('reels.mine') }}">ของฉัน</a>
        <a href="{{ route('favorites.index') }}">โปรด</a>
        <a href="{{ route('chat.index') }}">แชท</a>
    </nav>
</header>
@endauth

@if(session('success'))
    <div class="mx-auto mt-4 max-w-7xl px-4">
        <div class="rounded-lg border border-gold/40 bg-gold-champagne px-4 py-3 text-sm text-gold-dark">✦ {{ session('success') }}</div>
    </div>
@endif

<main>@yield('content')</main>

@auth
<footer class="mt-16 border-t border-gold/20 py-8 text-center text-xs text-stone-400">
    © {{ date('Y') }} REEL &amp; CO. — Premium Pre-owned Fishing Reels
</footer>
@endauth

@stack('scripts')
</body>
</html>
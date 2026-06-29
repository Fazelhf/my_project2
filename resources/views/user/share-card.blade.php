<!DOCTYPE html>
<html class="dark" lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پیش‌بینی {{ $user->name }} — {{ $game->homeTeam->name_fa ?? $game->homeTeam->name }} vs {{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}</title>
    <meta property="og:title" content="پیش‌بینی {{ $user->name }}">
    <meta property="og:description" content="{{ $game->homeTeam->name_fa ?? $game->homeTeam->name }} {{ $prediction?->home_score ?? '?' }} – {{ $prediction?->away_score ?? '?' }} {{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center min-h-screen antialiased" style="background:#0e141d;">

<div class="w-full max-w-md px-5 py-12">

    <div class="liquid-glass rounded-3xl p-8 text-center" id="share-card"
         style="background:linear-gradient(135deg,rgba(0,228,118,0.05),rgba(14,20,29,0.95));border:1px solid rgba(0,228,118,0.2);">

        <div class="mb-6">
            <p class="text-xs font-mono mb-1" style="color:rgba(185,203,185,0.4);">پیش‌بینی جام جهانی ۲۰۲۶</p>
            <div class="w-8 h-8 rounded-xl flex items-center justify-center mx-auto mb-2"
                 style="background:linear-gradient(135deg,#00b85e,#00e476);">
                <span class="material-symbols-outlined text-sm" style="color:#003919;font-variation-settings:'FILL' 1,'wght' 700;">sports_soccer</span>
            </div>
            <p class="text-sm font-bold text-white">{{ $user->name }}</p>
            @if($user->username)
                <p class="text-xs" style="color:rgba(185,203,185,0.4);">@{{ $user->username }}</p>
            @endif
        </div>

        {{-- Match --}}
        <div class="flex items-center justify-between gap-3 mb-8">
            <div class="flex-1 text-center">
                @if($game->homeTeam->flag_url)
                    <img src="{{ $game->homeTeam->flag_url }}" alt="{{ $game->homeTeam->code }}" class="w-16 h-11 object-cover rounded-xl mx-auto mb-2" style="border:2px solid rgba(255,255,255,0.15);" onerror="this.style.display='none'">
                @endif
                <p class="text-sm font-black font-heading text-white">{{ $game->homeTeam->name_fa ?? $game->homeTeam->name }}</p>
            </div>

            <div class="flex-shrink-0 text-center px-4">
                @if($prediction)
                    <div class="text-4xl font-black font-heading mb-1" style="color:#00e476;">
                        {{ $prediction->home_score }}–{{ $prediction->away_score }}
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full" style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.2);">پیش‌بینی من</span>
                @else
                    <div class="text-2xl font-black font-heading mb-1" style="color:rgba(255,255,255,0.2);">vs</div>
                    <span class="text-xs" style="color:rgba(185,203,185,0.4);">بدون پیش‌بینی</span>
                @endif
            </div>

            <div class="flex-1 text-center">
                @if($game->awayTeam->flag_url)
                    <img src="{{ $game->awayTeam->flag_url }}" alt="{{ $game->awayTeam->code }}" class="w-16 h-11 object-cover rounded-xl mx-auto mb-2" style="border:2px solid rgba(255,255,255,0.15);" onerror="this.style.display='none'">
                @endif
                <p class="text-sm font-black font-heading text-white">{{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}</p>
            </div>
        </div>

        @if($game->status === 'finished' && $prediction)
        <div class="mb-6">
            @php
                $pts = $prediction->points_override ?? $prediction->points_earned;
                $ptLabel = $pts >= 10 ? 'پیش‌بینی کامل!' : ($pts >= 7 ? 'نتیجه اختلاف گل' : ($pts >= 5 ? 'برنده/بازنده درست' : ($pts >= 2 ? 'شرکت‌کرده' : 'بدون امتیاز')));
            @endphp
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full" style="background:rgba(0,228,118,0.1);border:1px solid rgba(0,228,118,0.25);">
                <span class="material-symbols-outlined text-base" style="color:#00e476;">star</span>
                <span class="text-sm font-bold" style="color:#00e476;">{{ $ptLabel }} — {{ $pts ?? 0 }} امتیاز</span>
            </div>
        </div>
        @endif

        <p class="text-[10px]" style="color:rgba(185,203,185,0.2);">پیش‌بینی‌چی — WorldCup 2026</p>
    </div>

    {{-- Actions --}}
    <div class="mt-6 space-y-3">
        <button onclick="copyLink()" class="btn-primary w-full py-3">
            <span class="material-symbols-outlined text-base">link</span>
            کپی لینک
        </button>
        <a href="{{ route('games.show', $game) }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-2xl text-sm font-bold transition-all"
           style="background:rgba(255,255,255,0.05);color:rgba(185,203,185,0.8);border:1px solid rgba(255,255,255,0.1);">
            <span class="material-symbols-outlined text-base">arrow_forward</span>
            بازگشت به بازی
        </a>
    </div>

    <p class="text-center mt-6 text-xs" style="color:rgba(185,203,185,0.25);">ساخته شده با عشق در نمابر مهر</p>
</div>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.querySelector('button[onclick="copyLink()"]');
        btn.innerHTML = '<span class="material-symbols-outlined text-base">check_circle</span> کپی شد!';
        setTimeout(() => {
            btn.innerHTML = '<span class="material-symbols-outlined text-base">link</span> کپی لینک';
        }, 2000);
    });
}
</script>
</body>
</html>

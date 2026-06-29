<!DOCTYPE html>
<html class="dark" lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ورود') — پیش‌بینی‌چی</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v={{ filemtime(public_path('favicon.ico')) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center min-h-screen antialiased">

{{-- Grid + stadium mesh background --}}
<div class="stitch-bg"></div>
<svg class="fixed inset-0 w-full h-full pointer-events-none z-0 opacity-30" preserveAspectRatio="none">
    <defs>
        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(0,228,118,0.12)" stroke-width="0.5"/>
        </pattern>
    </defs>
    <rect width="100%" height="100%" fill="url(#grid)"/>
    <circle cx="50%" cy="50%" r="30%" fill="none" stroke="#00e476" stroke-width="0.3" stroke-dasharray="6 4"
            style="animation: dash 25s linear infinite;"/>
    <ellipse cx="50%" cy="50%" rx="42%" ry="25%" fill="none" stroke="#00e476" stroke-width="0.2" stroke-dasharray="4 6"
             style="animation: dash 18s linear infinite reverse;"/>
</svg>
<style>@keyframes dash { to { stroke-dashoffset: -120; } }</style>

<main class="relative z-10 w-full max-w-sm px-5 py-12 animate-fade-in">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center"
             style="background:linear-gradient(135deg,#00b85e,#00e476);box-shadow:0 0 32px rgba(0,228,118,0.3);">
            <span class="material-symbols-outlined text-2xl" style="color:#003919;font-variation-settings:'FILL' 1,'wght' 700,'GRAD' 0,'opsz' 24;">sports_soccer</span>
        </div>
        <h1 class="font-heading font-black text-3xl text-white tracking-tight leading-none">
            پیش‌بینی ۲۰۲۶
        </h1>
        <p class="mt-2 text-sm" style="color:rgba(185,203,185,0.6);">جام جهانی فوتبال در دستان شما</p>
    </div>

    {{-- Neon border card wrapper --}}
    <div class="relative rounded-3xl">
        <div class="absolute inset-0 rounded-3xl pointer-events-none"
             style="background:linear-gradient(45deg,transparent,rgba(0,228,118,0.25),transparent,rgba(0,228,118,0.15),transparent);background-size:200% 200%;animation:border-glow 5s linear infinite;z-index:-1;border-radius:1.5rem;padding:1px;">
        </div>
        <div class="liquid-glass rounded-3xl p-7 animate-scale-in stagger-1">
            @yield('content')
        </div>
    </div>

    {{-- Stats footer --}}
    <div class="mt-8 flex justify-center gap-8 animate-slide-up stagger-3" style="opacity:0.7;">
        <div class="text-center">
            <div class="font-heading font-black text-xl gradient-text-green">۴۸</div>
            <div class="text-xs font-mono mt-0.5" style="color:rgba(185,203,185,0.6);">تیم ملی</div>
        </div>
        <div class="text-center">
            <div class="font-heading font-black text-xl gradient-text-green">۱۰۴</div>
            <div class="text-xs font-mono mt-0.5" style="color:rgba(185,203,185,0.6);">مسابقه</div>
        </div>
    </div>
    <p class="text-center mt-6 text-xs animate-slide-up stagger-4" style="color:rgba(185,203,185,0.35);">ساخته شده با عشق در نمابر مهر</p>

</main>

<style>
@keyframes border-glow {
    0%   { background-position: 0% 50%; }
    100% { background-position: 200% 50%; }
}
</style>

@stack('scripts')
</body>
</html>

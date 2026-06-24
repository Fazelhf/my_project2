<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WorldCup Predictor') — WCP 2026</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen pattern-bg flex items-center justify-center p-4 antialiased">

    {{-- Ambient light orbs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[10%] w-[600px] h-[600px] rounded-full"
             style="background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%);"></div>
        <div class="absolute bottom-[-10%] right-[5%] w-[500px] h-[500px] rounded-full"
             style="background: radial-gradient(circle, rgba(16,185,129,0.06) 0%, transparent 70%);"></div>
        <div class="absolute top-[40%] left-[-10%] w-[400px] h-[400px] rounded-full"
             style="background: radial-gradient(circle, rgba(59,130,246,0.05) 0%, transparent 70%);"></div>
    </div>

    <div class="w-full max-w-md relative z-10">

        {{-- Brand Header --}}
        <div class="text-center mb-8 animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-4 animate-float"
                 style="background: linear-gradient(135deg, #1a1200, #0a0f1e); border: 1px solid rgba(245,158,11,0.3); box-shadow: 0 0 40px rgba(245,158,11,0.15), 0 20px 40px rgba(0,0,0,0.5);">
                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none">
                    <path d="M6 9H4.5a2.5 2.5 0 000 5H6" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M18 9h1.5a2.5 2.5 0 010 5H18" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M4 22h16" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M12 20v2" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M6 4h12v10a6 6 0 01-12 0V4z" stroke="#F59E0B" stroke-width="1.5" stroke-linejoin="round"/>
                    <circle cx="9" cy="9" r="1" fill="#FCD34D"/>
                    <circle cx="12" cy="7" r="1" fill="#FCD34D"/>
                    <circle cx="15" cy="9" r="1" fill="#FCD34D"/>
                </svg>
            </div>

            <h1 class="text-3xl font-black font-heading tracking-tight gradient-text-gold">
                WorldCup Predictor
            </h1>
            <p class="mt-1 text-brand-muted text-sm">
                جام جهانی ۲۰۲۶ — پیش‌بینی کن، امتیاز بگیر، برنده شو
            </p>
        </div>

        {{-- Auth Card --}}
        <div class="rounded-2xl p-8 animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.1s_both]"
             style="background: rgba(10,15,30,0.95); border: 1px solid #1E2D45; backdrop-filter: blur(20px); box-shadow: 0 0 0 1px rgba(245,158,11,0.05), 0 40px 80px rgba(0,0,0,0.6);">

            @yield('content')

        </div>

        <p class="text-center text-xs text-brand-subtle mt-6 animate-[fade-in_0.5s_0.3s_both]">
            سیستم داخلی پیش‌بینی جام جهانی ۲۰۲۶
        </p>
    </div>

</body>
</html>

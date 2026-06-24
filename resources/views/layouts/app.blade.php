<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WorldCup Predictor') — WCP 2026</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="pattern-bg text-brand-text font-sans antialiased" x-data="{ sidebar: false }" x-cloak>

<div class="flex min-h-screen">

    {{-- ── Mobile Overlay ─────────────────────────────────── --}}
    <div x-show="sidebar" x-transition.opacity @click="sidebar = false"
         class="fixed inset-0 z-20 bg-black/80 backdrop-blur-sm lg:hidden"></div>

    {{-- ═══════════════════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════════════════ --}}
    <aside :class="sidebar ? 'translate-x-0' : 'translate-x-full'"
           class="fixed top-0 right-0 z-30 h-full w-68 flex flex-col
                  transition-transform duration-300 ease-in-out
                  lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen"
           style="width: 260px; background: linear-gradient(180deg, #0d1525 0%, #0a0f1e 100%); border-left: 1px solid #1E2D45;">

        {{-- Logo / Brand --}}
        <div class="flex items-center gap-3 px-5 h-16 flex-shrink-0" style="border-bottom: 1px solid #1E2D45;">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: linear-gradient(135deg, #92400E, #D97706); box-shadow: 0 0 20px rgba(245,158,11,0.3);">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none">
                    <path d="M6 9H4.5a2.5 2.5 0 000 5H6M18 9h1.5a2.5 2.5 0 010 5H18M6 4h12v10a6 6 0 01-12 0V4z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    <circle cx="12" cy="9" r="1.5" fill="currentColor"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-black font-heading leading-none gradient-text-gold">WorldCup</p>
                <p class="text-[11px] text-brand-muted mt-0.5 font-sans">Predictor 2026</p>
            </div>
        </div>

        {{-- Score Card in Sidebar --}}
        <div class="mx-3 mt-4 mb-2 rounded-xl p-3 flex items-center gap-3"
             style="background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(16,185,129,0.05)); border: 1px solid rgba(245,158,11,0.2);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 font-black text-sm font-heading"
                 style="background: rgba(0,0,0,0.3); color: #F59E0B;">
                {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'U', 0, 1, 'UTF-8')) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-brand-text truncate leading-none">{{ auth()->user()?->name }}</p>
                <p class="text-[11px] text-brand-muted mt-1 truncate">{{ auth()->user()?->department ?? 'کاربر' }}</p>
            </div>
            <div class="flex-shrink-0 text-center">
                <p class="text-lg font-black font-heading leading-none" style="color: #F59E0B;">{{ auth()->user()?->total_score ?? 0 }}</p>
                <p class="text-[10px] text-brand-subtle mt-0.5">امتیاز</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-0.5">

            @php
                $nav = [
                    ['route' => 'dashboard',   'label' => 'داشبورد',          'match' => 'dashboard',   'icon' => 'home'],
                    ['route' => 'games.index', 'label' => 'پیش‌بینی بازی‌ها', 'match' => 'games.*',     'icon' => 'calendar'],
                    ['route' => 'leaderboard', 'label' => 'جدول رده‌بندی',    'match' => 'leaderboard', 'icon' => 'trophy'],
                ];
            @endphp

            @foreach($nav as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group cursor-pointer"
                   style="{{ $active
                       ? 'background: linear-gradient(135deg, rgba(245,158,11,0.12), rgba(16,185,129,0.06)); border: 1px solid rgba(245,158,11,0.2); color: #F59E0B;'
                       : 'border: 1px solid transparent; color: #8AAABB;' }}"
                   onmouseover="{{ !$active ? "this.style.background='rgba(255,255,255,0.04)'; this.style.borderColor='#1E2D45'; this.style.color='#F1F5F9'" : '' }}"
                   onmouseout="{{ !$active ? "this.style.background='transparent'; this.style.borderColor='transparent'; this.style.color='#8AAABB'" : '' }}">

                    @if($item['icon'] === 'home')
                        <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    @elseif($item['icon'] === 'calendar')
                        <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @else
                        <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    @endif

                    <span>{{ $item['label'] }}</span>

                    @if($active)
                        <div class="mr-auto flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: #F59E0B;"></span>
                        </div>
                    @endif
                </a>
            @endforeach

            {{-- Admin section --}}
            @if(auth()->user()?->is_admin)
                <div class="pt-4 mt-3" style="border-top: 1px solid #1E2D45;">
                    <p class="px-3 mb-2 text-[10px] font-bold text-brand-subtle uppercase tracking-widest">مدیریت</p>
                    @php $adminActive = request()->routeIs('admin.*'); @endphp
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 cursor-pointer"
                       style="{{ $adminActive
                           ? 'background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(59,130,246,0.08)); border: 1px solid rgba(139,92,246,0.3); color: #C4B5FD;'
                           : 'border: 1px solid transparent; color: #8AAABB;' }}"
                       onmouseover="{{ !$adminActive ? "this.style.background='rgba(255,255,255,0.04)'; this.style.borderColor='#1E2D45'; this.style.color='#F1F5F9'" : '' }}"
                       onmouseout="{{ !$adminActive ? "this.style.background='transparent'; this.style.borderColor='transparent'; this.style.color='#8AAABB'" : '' }}">
                        <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>پنل ادمین</span>
                    </a>
                </div>
            @endif
        </nav>

        {{-- Logout footer --}}
        <div class="flex-shrink-0 p-3" style="border-top: 1px solid #1E2D45;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 cursor-pointer"
                        style="border: 1px solid transparent; color: #8AAABB;"
                        onmouseover="this.style.background='rgba(239,68,68,0.08)'; this.style.borderColor='rgba(239,68,68,0.2)'; this.style.color='#FCA5A5'"
                        onmouseout="this.style.background='transparent'; this.style.borderColor='transparent'; this.style.color='#8AAABB'">
                    <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>خروج از حساب</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══════════════════════════════════════════════════════
         MAIN AREA
    ═══════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Topbar --}}
        <header class="sticky top-0 z-10 flex items-center h-14 px-4 sm:px-6 gap-4 flex-shrink-0"
                style="background: rgba(10,15,30,0.85); backdrop-filter: blur(16px); border-bottom: 1px solid #1E2D45;">

            {{-- Mobile menu toggle --}}
            <button @click="sidebar = !sidebar"
                    class="lg:hidden p-2 -mr-1 rounded-lg cursor-pointer transition-all duration-150"
                    style="color: #8AAABB;"
                    onmouseover="this.style.color='#F1F5F9'; this.style.background='rgba(255,255,255,0.05)'"
                    onmouseout="this.style.color='#8AAABB'; this.style.background='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title --}}
            <h1 class="flex-1 text-base font-black font-heading text-brand-text truncate tracking-wide">
                @yield('page-title', '')
            </h1>

            {{-- Score badge --}}
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg"
                 style="background: linear-gradient(135deg, rgba(245,158,11,0.12), rgba(245,158,11,0.06)); border: 1px solid rgba(245,158,11,0.25);">
                <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#F59E0B">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-sm font-black font-heading" style="color: #F59E0B;">{{ auth()->user()?->total_score ?? 0 }}</span>
                <span class="text-xs text-brand-muted hidden sm:block">امتیاز</span>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 text-sm rounded-xl px-4 py-3 animate-[slide-up_0.3s_ease_both] score-green">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 text-sm rounded-xl px-4 py-3 animate-[slide-up_0.3s_ease_both] score-red">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 px-4 sm:px-6 py-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>

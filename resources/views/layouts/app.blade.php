<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WorldCup Predictor') — WCP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-bg text-brand-text font-sans antialiased" x-data="{ sidebar: true }" x-cloak>

<div class="flex min-h-screen">

    {{-- ── Mobile Overlay ───────────────────────────────── --}}
    <div
        x-show="sidebar"
        x-transition.opacity
        @click="sidebar = false"
        class="fixed inset-0 z-20 bg-black/70 backdrop-blur-sm lg:hidden"
    ></div>

    {{-- ═══════════════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════════════ --}}
    <aside
        :class="sidebar ? 'translate-x-0' : 'translate-x-full'"
        class="fixed top-0 right-0 z-30 h-full w-64 flex flex-col
               bg-brand-surface border-l border-brand-border
               transition-transform duration-200 ease-in-out
               lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 h-16 border-b border-brand-border flex-shrink-0">
            <div class="w-8 h-8 rounded-lg bg-brand-green flex items-center justify-center flex-shrink-0 shadow-md shadow-brand-green/30">
                <svg class="w-4.5 h-4.5 text-black" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold font-heading leading-none text-brand-text truncate">WorldCup</p>
                <p class="text-[11px] text-brand-muted mt-0.5">Predictor 2026</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            @php
                function navItem(string $route, string $label, string $match): array {
                    return ['route' => $route, 'label' => $label, 'match' => $match];
                }
                $nav = [
                    navItem('dashboard',   'داشبورد',         'dashboard'),
                    navItem('games.index', 'پیش‌بینی بازی‌ها','games.*'),
                    navItem('leaderboard', 'جدول رده‌بندی',   'leaderboard'),
                ];
            @endphp

            @foreach($nav as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   @class([
                       'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150',
                       'bg-brand-card text-brand-green'         => $active,
                       'text-brand-muted hover:bg-brand-card hover:text-brand-text' => !$active,
                   ])>
                    @if($item['route'] === 'dashboard')
                        <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    @elseif($item['route'] === 'games.index')
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
                        <span class="mr-auto w-1.5 h-1.5 rounded-full bg-brand-green"></span>
                    @endif
                </a>
            @endforeach

            {{-- Admin section --}}
            @if(auth()->user()?->is_admin)
                <div class="pt-4 mt-4 border-t border-brand-border">
                    <p class="px-3 mb-2 text-[10px] font-semibold text-brand-subtle uppercase tracking-widest">مدیریت</p>
                    <a href="{{ route('admin.dashboard') }}"
                       @class([
                           'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150',
                           request()->routeIs('admin.*')
                               ? 'bg-brand-card text-brand-green'
                               : 'text-brand-muted hover:bg-brand-card hover:text-brand-text',
                       ])>
                        <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>پنل ادمین</span>
                    </a>
                </div>
            @endif
        </nav>

        {{-- User footer --}}
        <div class="flex-shrink-0 border-t border-brand-border p-3">
            <div class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-brand-card transition-colors duration-150 group">
                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full bg-brand-green flex items-center justify-center flex-shrink-0 text-xs font-bold text-black">
                    {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'U', 0, 1, 'UTF-8')) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-brand-text truncate leading-none">{{ auth()->user()?->name }}</p>
                    <p class="text-[11px] text-brand-muted mt-0.5 truncate">{{ auth()->user()?->department ?? 'کاربر' }}</p>
                </div>
                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit"
                            class="p-1.5 rounded-lg text-brand-subtle hover:text-brand-red hover:bg-red-950/30 transition-colors duration-150 cursor-pointer"
                            title="خروج">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════════════════════════════════════
         MAIN AREA
    ═══════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Topbar --}}
        <header class="sticky top-0 z-10 flex items-center h-16 px-4 sm:px-6 gap-4
                       bg-brand-surface/80 backdrop-blur border-b border-brand-border flex-shrink-0">
            {{-- Mobile toggle --}}
            <button @click="sidebar = !sidebar"
                    class="lg:hidden p-2 -mr-1 rounded-lg text-brand-muted hover:text-brand-text hover:bg-brand-card transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title --}}
            <h1 class="flex-1 text-base font-semibold font-heading text-brand-text truncate">
                @yield('page-title', '')
            </h1>

            {{-- Score chip --}}
            <div class="flex items-center gap-2 bg-brand-card border border-brand-border px-3 py-1.5 rounded-lg">
                <svg class="w-4 h-4 text-brand-amber flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-sm font-bold text-brand-green">{{ auth()->user()?->total_score ?? 0 }}</span>
                <span class="text-xs text-brand-muted hidden sm:block">امتیاز</span>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 bg-green-950/50 border border-green-800/50 text-green-300 text-sm rounded-xl px-4 py-3">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 bg-red-950/50 border border-red-800/50 text-red-300 text-sm rounded-xl px-4 py-3">
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

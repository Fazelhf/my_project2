<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پیش‌بینی جام جهانی') — WorldCup Predictor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body style="background-color:#020617; color:#F8FAFC; font-family:'Open Sans',ui-sans-serif,sans-serif;">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- ── Sidebar Overlay (mobile) ─────────────────────────────── --}}
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-black/60 lg:hidden"
    ></div>

    {{-- ── Sidebar ──────────────────────────────────────────────── --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
        class="fixed top-0 right-0 z-30 h-full w-64 flex flex-col transition-transform duration-200
               border-l lg:sticky lg:top-0 lg:h-screen"
        style="background-color:#0F172A; border-color:#334155;"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b" style="border-color:#334155;">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color:#22C55E;">
                <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M12 2a10 10 0 0 1 0 20A10 10 0 0 1 12 2z" stroke="none"/>
                    <path fill="black" d="M12 4c-1.1 0-2.8 2.4-3.4 5h6.8C14.8 6.4 13.1 4 12 4zm0 16c1.1 0 2.8-2.4 3.4-5H8.6c.6 2.6 2.3 5 3.4 5zm-5.2-7H4.1a8 8 0 0 0 3.5 5.3C7 16.6 6.4 14.9 6.8 13zm10.4 0c.4 1.9-.2 3.6-.8 5.3A8 8 0 0 0 19.9 13h-2.7zm-10.4-2H4.1A8 8 0 0 1 7.6 5.7C7 7.4 6.4 9.1 6.8 11zm10.4 0c.4-1.9-.2-3.6-.8-5.3A8 8 0 0 1 19.9 11h-2.7z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold leading-tight" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">WorldCup</p>
                <p class="text-xs" style="color:#94A3B8;">Predictor 2026</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @php
                $navItems = [
                    ['route' => 'dashboard',   'label' => 'داشبورد',          'match' => 'dashboard'],
                    ['route' => 'games.index', 'label' => 'پیش‌بینی بازی‌ها', 'match' => 'games.*'],
                    ['route' => 'leaderboard', 'label' => 'جدول رده‌بندی',    'match' => 'leaderboard'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 cursor-pointer"
                   style="{{ $active
                       ? 'background-color:#1E293B; color:#22C55E;'
                       : 'color:#94A3B8;' }}"
                   onmouseover="{{ $active ? '' : "this.style.backgroundColor='#1E293B'; this.style.color='#F8FAFC';" }}"
                   onmouseout="{{ $active ? '' : "this.style.backgroundColor=''; this.style.color='#94A3B8';" }}"
                >
                    @if($active)
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color:#22C55E;"></span>
                    @else
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color:transparent;"></span>
                    @endif
                    {{ $item['label'] }}
                </a>
            @endforeach

            @if(auth()->user()?->is_admin)
                <div class="pt-3 mt-3 border-t" style="border-color:#334155;">
                    <p class="px-3 mb-1 text-xs uppercase tracking-wider" style="color:#475569;">مدیریت</p>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 cursor-pointer"
                       style="color:#94A3B8;"
                       onmouseover="this.style.backgroundColor='#1E293B'; this.style.color='#F8FAFC';"
                       onmouseout="this.style.backgroundColor=''; this.style.color='#94A3B8';"
                    >
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color:transparent;"></span>
                        پنل ادمین
                    </a>
                </div>
            @endif
        </nav>

        {{-- User Footer --}}
        <div class="px-3 py-4 border-t" style="border-color:#334155;">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold"
                     style="background-color:#22C55E; color:#020617;">
                    {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'U', 0, 2, 'UTF-8')) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate" style="color:#F8FAFC;">{{ auth()->user()?->name }}</p>
                    <p class="text-xs truncate" style="color:#94A3B8;">{{ auth()->user()?->department ?? 'کاربر' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 cursor-pointer"
                        style="background-color:#1E293B; color:#94A3B8; border:1px solid #334155;"
                        onmouseover="this.style.color='#F87171'; this.style.borderColor='#F87171';"
                        onmouseout="this.style.color='#94A3B8'; this.style.borderColor='#334155';"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    خروج
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Area ────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top Header --}}
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 px-4 sm:px-6 h-14 border-b"
                style="background-color:#0F172A; border-color:#334155;">
            {{-- Mobile hamburger --}}
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 rounded-lg cursor-pointer"
                    style="color:#94A3B8;"
                    onmouseover="this.style.backgroundColor='#1E293B';"
                    onmouseout="this.style.backgroundColor='';">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="text-base font-semibold flex-1" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">
                @yield('page-title', '')
            </h1>

            {{-- Score badge --}}
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium"
                 style="background-color:#1E293B; color:#22C55E;">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                {{ auth()->user()?->total_score ?? 0 }} امتیاز
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2"
                 style="background-color:#14532d; color:#86efac; border:1px solid #16a34a;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2"
                 style="background-color:#450a0a; color:#fca5a5; border:1px solid #dc2626;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 px-4 sm:px-6 py-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>

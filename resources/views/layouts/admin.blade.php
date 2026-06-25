<!DOCTYPE html>
<html class="dark" lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پنل مدیریت') — Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Vazirmatn:wght@400;700&family=JetBrains+Mono:wght@500&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <style>
        body { background-color: #0e141d; font-family: 'Vazirmatn', sans-serif; color: #dde2f0; }
        .liquid-glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.05), 0 10px 30px -10px rgba(0,0,0,0.3);
            position: relative; overflow: hidden;
        }
        .liquid-glass::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.07) 0%, transparent 50%, rgba(255,255,255,0.03) 100%);
            pointer-events: none;
        }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        /* Keep brand vars for backward compat */
        .bg-brand-bg { background-color: #0e141d; }
        .bg-brand-surface { background: rgba(255,255,255,0.03); }
        .bg-brand-card { background: rgba(255,255,255,0.05); }
        .border-brand-border { border-color: rgba(255,255,255,0.1); }
        .text-brand-text { color: #dde2f0; }
        .text-brand-muted { color: #b9cbb9; }
        .text-brand-subtle { color: rgba(185,203,185,0.5); }
        .text-brand-green { color: #00e476; }
        .text-brand-blue { color: #4D9FFF; }
        .text-brand-amber { color: #F59E0B; }
        .text-brand-red { color: #FF8A8A; }
        .bg-brand-green { background: #00e476; }
        .hover\:bg-brand-green-dim:hover { background: #00b85e; }
        .font-heading { font-family: 'Plus Jakarta Sans', 'Vazirmatn', sans-serif; }
        .font-sans { font-family: 'Vazirmatn', sans-serif; }
    </style>
</head>
<body class="text-brand-text font-sans antialiased" x-data="{ sidebar: false }" x-cloak>

<div class="flex min-h-screen">

    <div
        x-show="sidebar"
        x-transition.opacity
        @click="sidebar = false"
        class="fixed inset-0 z-20 bg-black/70 backdrop-blur-sm lg:hidden"
    ></div>

    {{-- Admin Sidebar --}}
    <aside
        :class="sidebar ? 'translate-x-0' : 'translate-x-full'"
        class="fixed top-0 right-0 z-30 h-full w-64 flex flex-col
               liquid-glass border-l
               transition-transform duration-200 ease-in-out
               lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen"
        style="background:rgba(14,20,29,0.85);"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 h-16 border-b border-brand-border flex-shrink-0">
            <div class="w-8 h-8 rounded-lg bg-brand-green flex items-center justify-center flex-shrink-0 shadow-md shadow-brand-green/30">
                <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold font-heading leading-none text-brand-text truncate">پنل مدیریت</p>
                <p class="text-[11px] text-brand-muted mt-0.5">WorldCup 2026</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard',   'label' => 'داشبورد',  'match' => 'admin.dashboard'],
                    ['route' => 'admin.teams.index', 'label' => 'تیم‌ها',   'match' => 'admin.teams.*'],
                    ['route' => 'admin.games.index', 'label' => 'بازی‌ها',  'match' => 'admin.games.*'],
                ];
            @endphp

            @foreach($adminNav as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   @class([
                       'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150',
                       'bg-brand-card text-brand-green'                                  => $active,
                       'text-brand-muted hover:bg-brand-card hover:text-brand-text'      => !$active,
                   ])>
                    <span @class([
                        'w-1.5 h-1.5 rounded-full flex-shrink-0',
                        'bg-brand-green' => $active,
                        'bg-transparent' => !$active,
                    ])></span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            <div class="pt-4 mt-4 border-t border-brand-border">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                          text-brand-muted hover:bg-brand-card hover:text-brand-text">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>بازگشت به سایت</span>
                </a>
            </div>
        </nav>

        {{-- User footer --}}
        <div class="flex-shrink-0 border-t border-brand-border p-3">
            <div class="flex items-center gap-3 px-2 py-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-brand-green flex items-center justify-center flex-shrink-0 text-xs font-bold text-black">
                    {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'A', 0, 1, 'UTF-8')) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-brand-text truncate leading-none">{{ auth()->user()?->name }}</p>
                    <p class="text-[11px] text-brand-green mt-0.5">مدیر سیستم</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium
                               bg-brand-card border border-brand-border text-brand-muted
                               hover:text-brand-red hover:border-brand-red/50
                               transition-colors duration-150 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    خروج
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="sticky top-0 z-10 flex items-center h-16 px-4 sm:px-6 gap-4
                       bg-brand-surface/80 backdrop-blur border-b border-brand-border flex-shrink-0">
            <button @click="sidebar = !sidebar"
                    class="lg:hidden p-2 -mr-1 rounded-lg text-brand-muted hover:text-brand-text hover:bg-brand-card transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="flex-1 text-base font-semibold font-heading text-brand-text truncate">
                @yield('page-title', 'داشبورد')
            </h1>
            <span class="text-xs px-2.5 py-1 rounded-lg font-semibold bg-brand-card border border-brand-border text-brand-green">
                Admin
            </span>
        </header>

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

        <main class="flex-1 px-4 sm:px-6 py-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>

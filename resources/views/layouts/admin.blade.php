<!DOCTYPE html>
<html class="dark" lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'پنل مدیریت') — Admin</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v={{ filemtime(public_path('favicon.ico')) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="antialiased" x-data="{ sidebar: false }" x-cloak>

<div class="stitch-bg"></div>

<div class="flex min-h-screen relative z-10">

    {{-- Mobile overlay --}}
    <div x-show="sidebar" x-transition.opacity @click="sidebar = false"
         class="fixed inset-0 z-20 bg-black/70 backdrop-blur-sm lg:hidden"></div>

    {{-- Admin Sidebar ──────────────────────────────────────────────────────── --}}
    <aside :class="sidebar ? 'translate-x-0' : 'translate-x-full'"
           class="fixed top-0 right-0 z-30 h-full w-64 flex flex-col
                  liquid-glass border-l border-outline
                  transition-transform duration-300 ease-out
                  lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen"
           style="background:rgba(14,20,29,0.9);">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 h-16 flex-shrink-0"
             style="border-bottom:1px solid rgba(255,255,255,0.08);">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#00b85e,#00e476);box-shadow:0 0 12px rgba(0,228,118,0.3);">
                <span class="material-symbols-outlined text-base" style="color:#003919;font-size:18px;font-variation-settings:'FILL' 1,'wght' 700,'GRAD' 0,'opsz' 24;">settings</span>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold font-heading leading-none text-white truncate">پنل مدیریت</p>
                <p class="text-[11px] mt-0.5" style="color:#00e476;">WorldCup 2026</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard',   'label' => 'داشبورد', 'icon' => 'dashboard',     'match' => 'admin.dashboard'],
                    ['route' => 'admin.teams.index',  'label' => 'تیم‌ها',  'icon' => 'flag',           'match' => 'admin.teams.*'],
                    ['route' => 'admin.games.index',  'label' => 'بازی‌ها', 'icon' => 'sports_soccer',  'match' => 'admin.games.*'],
                    ['route' => 'admin.users.index',    'label' => 'کاربران',             'icon' => 'group',         'match' => 'admin.users.*'],
                    ['route' => 'admin.scoring-rules.index', 'label' => 'امتیازدهی',      'icon' => 'rule',          'match' => 'admin.scoring-rules.*'],
                    ['route' => 'admin.audit-log',      'label' => 'Audit Log',            'icon' => 'policy',        'match' => 'admin.audit-log'],
                    ['route' => 'admin.import-export',  'label' => 'ایمپورت / اکسپورت',   'icon' => 'sync_alt',      'match' => 'admin.import-export'],
                ];
            @endphp

            @foreach($adminNav as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 group"
                   style="{{ $active
                       ? 'background:rgba(0,228,118,0.1);color:#00e476;'
                       : 'color:rgba(185,203,185,0.7);' }}"
                   onmouseover="{{ !$active ? "this.style.background='rgba(255,255,255,0.05)';this.style.color='#dde2f0'" : '' }}"
                   onmouseout="{{ !$active ? "this.style.background='';this.style.color='rgba(185,203,185,0.7)'" : '' }}">
                    <span class="material-symbols-outlined text-base flex-shrink-0"
                          style="font-variation-settings:{{ $active ? "'FILL' 1" : "'FILL' 0" }},'wght' 400,'GRAD' 0,'opsz' 24;">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                    @if($active)
                    <div class="mr-auto w-1.5 h-1.5 rounded-full" style="background:#00e476;"></div>
                    @endif
                </a>
            @endforeach

            <div class="pt-4 mt-4" style="border-top:1px solid rgba(255,255,255,0.07);">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
                   style="color:rgba(185,203,185,0.6);"
                   onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='#dde2f0'"
                   onmouseout="this.style.background='';this.style.color='rgba(185,203,185,0.6)'">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    <span>بازگشت به سایت</span>
                </a>
            </div>
        </nav>

        {{-- User footer --}}
        <div class="flex-shrink-0 p-3" style="border-top:1px solid rgba(255,255,255,0.07);">
            <div class="flex items-center gap-3 px-2 py-2 mb-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-black"
                     style="background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;">
                    {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'A', 0, 1, 'UTF-8')) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate leading-none">{{ auth()->user()?->name }}</p>
                    <p class="text-[11px] mt-0.5" style="color:#00e476;">مدیر سیستم</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-sm font-medium cursor-pointer transition-all duration-150"
                        style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(185,203,185,0.7);"
                        onmouseover="this.style.background='rgba(255,90,90,0.08)';this.style.borderColor='rgba(255,90,90,0.3)';this.style.color='#FF8A8A'"
                        onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.borderColor='rgba(255,255,255,0.08)';this.style.color='rgba(185,203,185,0.7)'">
                    <span class="material-symbols-outlined text-base">logout</span>
                    خروج
                </button>
            </form>
            <p class="text-center mt-3 text-[10px]" style="color:rgba(185,203,185,0.2);">ساخته شده با عشق در نمابر مهر</p>
        </div>
    </aside>

    {{-- Main Area ──────────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top bar --}}
        <header class="sticky top-0 z-10 flex items-center h-16 px-4 sm:px-6 gap-4 flex-shrink-0"
                style="background:rgba(14,20,29,0.7);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.07);">
            <button @click="sidebar = !sidebar"
                    class="lg:hidden p-2 -mr-1 rounded-xl transition-all cursor-pointer"
                    style="color:rgba(185,203,185,0.7);"
                    onmouseover="this.style.background='rgba(255,255,255,0.06)';this.style.color='#dde2f0'"
                    onmouseout="this.style.background='';this.style.color='rgba(185,203,185,0.7)'">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <h1 class="flex-1 text-base font-bold font-heading text-white truncate">
                @yield('page-title', 'داشبورد')
            </h1>
            <span class="badge badge-green font-mono text-xs">Admin</span>
        </header>

        {{-- Flash --}}
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold animate-slide-up flash-success" data-flash>
                <span class="material-symbols-outlined text-base flex-shrink-0">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold animate-slide-up flash-error" data-flash>
                <span class="material-symbols-outlined text-base flex-shrink-0">error</span>
                {{ session('error') }}
            </div>
        @endif

        <main class="flex-1 px-4 sm:px-6 py-6 page-enter">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پنل مدیریت') — Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body style="background-color:#020617; color:#F8FAFC; font-family:'Open Sans',ui-sans-serif,sans-serif;">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-black/60 lg:hidden"
    ></div>

    {{-- Admin Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
        class="fixed top-0 right-0 z-30 h-full w-64 flex flex-col transition-transform duration-200 border-l lg:sticky lg:top-0 lg:h-screen"
        style="background-color:#0F172A; border-color:#334155;"
    >
        <div class="flex items-center gap-3 px-5 py-5 border-b" style="border-color:#334155;">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color:#22C55E;">
                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold leading-tight" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">پنل مدیریت</p>
                <p class="text-xs" style="color:#94A3B8;">WorldCup 2026</p>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard',    'label' => 'داشبورد',    'match' => 'admin.dashboard'],
                    ['route' => 'admin.teams.index',  'label' => 'تیم‌ها',     'match' => 'admin.teams.*'],
                    ['route' => 'admin.games.index',  'label' => 'بازی‌ها',    'match' => 'admin.games.*'],
                ];
            @endphp

            @foreach($adminNav as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 cursor-pointer"
                   style="{{ $active ? 'background-color:#1E293B; color:#22C55E;' : 'color:#94A3B8;' }}"
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

            <div class="pt-3 mt-3 border-t" style="border-color:#334155;">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 cursor-pointer"
                   style="color:#94A3B8;"
                   onmouseover="this.style.backgroundColor='#1E293B'; this.style.color='#F8FAFC';"
                   onmouseout="this.style.backgroundColor=''; this.style.color='#94A3B8';"
                >
                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color:transparent;"></span>
                    بازگشت به سایت
                </a>
            </div>
        </nav>

        <div class="px-3 py-4 border-t" style="border-color:#334155;">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold"
                     style="background-color:#22C55E; color:#020617;">
                    {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'A', 0, 2, 'UTF-8')) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate" style="color:#F8FAFC;">{{ auth()->user()?->name }}</p>
                    <p class="text-xs" style="color:#22C55E;">مدیر سیستم</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 cursor-pointer"
                        style="background-color:#1E293B; color:#94A3B8; border:1px solid #334155;"
                        onmouseover="this.style.color='#F87171'; this.style.borderColor='#F87171';"
                        onmouseout="this.style.color='#94A3B8'; this.style.borderColor='#334155';">
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
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 px-4 sm:px-6 h-14 border-b"
                style="background-color:#0F172A; border-color:#334155;">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 rounded-lg cursor-pointer"
                    style="color:#94A3B8;"
                    onmouseover="this.style.backgroundColor='#1E293B';"
                    onmouseout="this.style.backgroundColor='';">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-base font-semibold flex-1" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">
                @yield('page-title', 'داشبورد')
            </h1>
            <span class="text-xs px-2 py-1 rounded-md font-medium" style="background-color:#1E293B; color:#22C55E;">
                Admin
            </span>
        </header>

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

        <main class="flex-1 px-4 sm:px-6 py-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
